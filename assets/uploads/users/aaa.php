<?php
// echo phpversion();
// echo "<br>";
// header('Content-Type: text/plain');
// require_once('settings.inc');

require_once ('modules/api_handler.inc');
require_once ('modules/GoogleAPI.php');
require_once ('twitteroauth/twitteroauth.php');
require_once ('twitteroauth/config.php');
require_once ( dirname(__DIR__) . '/vendor/recurly_lib/recurly.php');
require_once ('modules/simple_html_dom.php');

Recurly_Client::$subdomain = RECURLY_SUB_DOMAIN;
Recurly_Client::$apiKey = RECURLY_API_KEY;

$api = new ApiHandler();

// Try to setup our session
// Utilities::eClincherSetupUserSession();

// For analytics/backtracing
$bt = debug_backtrace();
$log_response_to_ec_error_handler = false;
array_shift($bt);
$ajax_time_started = microtime(TRUE);
$ajax_label = 'Unknown';
if ($log_response_to_ec_error_handler) {
    ob_start();
}

// Detect and respond to our users that we're in maintenance mode > 1
if ( ECConfig::get('MAINTENANCE_MODE') > 1 ) 
{
    // echo "Reload page";
    echo file_get_contents( ROOT_PATH . '/web/maintenance.html');
    exit;
}

// This ensures we write the end log, even if an exit or die() is called anywhere below
register_shutdown_function('ajaxlog_write_close');

date_default_timezone_set( "UTC" );

if ($_SERVER ['REQUEST_METHOD'] == "GET") 
{
    if (isset ( $_GET ['type'] )) 
    {
        // For Farley's debugging and reporting of API calls
        $ajax_label = 'GET:type:' . $_GET ['type'] . ' by ' . (!empty($_SESSION['user_name']) ? $_SESSION['user_name'] : 'not logged in');
        ECErrorHandler::AJAXLog('Request for ' . $ajax_label, '', $bt);
        
        //debug:
        // echo "<script>window.opener.FinishedAddAccount({type:'whatever you just added',returnCode:'SUCCESS',message:'Unexpected error has occurred. Please contact our <a href=\"/contact\">support team</a> If problem persists.'});window.close();</script>";
        
        switch ($_GET ['type']) {
            case 'fb' :
                // if (!(isset ( $_COOKIE ['processingFB'] ) && $_COOKIE ['processingFB'] == "true")) {
                //     setcookie ("processingFB", "true", time () + 10);
                    $api->addFbAccount (true);
                // }else{
                //     echo "<script>window.opener.FinishedAddAccount({returnCode:'FAIL',message:'Unexpected error has occurred. Please contact our <a href=\"/contact\">support team</a> If problem persists.'});window.close();</script>";
                // }
                break;
            case 'ln' :
                $api->addLnAccount (true);
                break;
            case 'tw' :
                $api->addTwAccount (true);
                break;
            case 'in' :
                $api->addInAccount (true);
                break;
            case "fb_exp" :
                $api->setExpiredKey ( $_GET ["nickname"], $_GET ["user_id"], true );
                break;
            case 'facebook':
                $api->addFbAccount ();
                break;
            case 'twitter':
                $api->addTwAccount ();
                break;
            case 'google':
                $api->addGaAccount ();
                break;
            case 'youtube':
                $api->addGaAccount ();
                break;
            case 'googleplus':
                $api->addGaAccount ();
                break;
            case 'linkedin':
                $api->addLnAccount ();
                break;
            case 'instagram':
                $api->addInAccount ();
                break; 
            case 'blogger':
                $api->addGaAccount ();
                break;  
        }
	} 

    elseif (isset ( $_GET ['code'] )) 
    {
        $ajax_label = 'GET:code:' . $_GET ['code'] . ' by ' . (!empty($_SESSION['user_name']) ? $_SESSION['user_name'] : 'not logged in');
        ECErrorHandler::AJAXLog('Request for ' . $ajax_label, '', $bt);
		$api->addGaAccount ( $_COOKIE ['ga_nickname'] );
	} 

    elseif (isset ( $_GET ['error'] )) 
    {
        $ajax_label = 'GET:error:' . $_GET ['error'] . ' by ' . (!empty($_SESSION['user_name']) ? $_SESSION['user_name'] : 'not logged in');
        ECErrorHandler::AJAXLog('Request for ' . $ajax_label, '', $bt);
		setcookie ( "ga_nickname", "", time () - 3600 );
		setcookie ( "type", "", time () - 3600 );
        echo "<script>window.opener.FinishedAddAccount({returnCode:'FAIL',message:'".$_GET ['error']."'});window.close();</script>";
	} 

    elseif (isset ( $_GET ['action'] )) 
    {
        $ajax_label = 'GET:action:' . $_GET ['action'] . ' by ' . (!empty($_SESSION['user_name']) ? $_SESSION['user_name'] : 'not logged in');
        ECErrorHandler::AJAXLog('Request for ' . $ajax_label, '', $bt);
        
		switch ( $_GET['action'] ):

            case "savedUrls":

                $urls = $api->getSavedUrls();

                echo json_encode( $urls );

                break;

            case 'checkIfWixUser':
                $is_wix_user = $this->api->getSettings()->settings->wixUser;
                echo $is_wix_user;
                break;

			case 'deleteAccountByID':
				if ( isset( $_GET ['id'] ) )
				{
					$id = $_GET ['id'];
					$accounts_obj = new SimpleXMLElement( $api->getAccountsData ( 'accounts' ) );
					$accounts = $accounts_obj->analyticsAccounts->account;

					for ( $i = 0; $i < count( $accounts ); $i++ )
					{
						if ( $accounts[$i]->accountId == $id )
                        {
                            echo $api->deleteAnalyticsAccount ( $id );

                            MultiCache::cache_delete('ApiHandler:getWidgetFeeds:' . $_SESSION['user_name'] . ':' . $_SESSION['user_pass'] );
                        }
					}
				}

				else echo "no accountId provided";

				break;

			case 'deleteAccount' :
				if (isset ( $_GET ['val'] )) {

					$i = intval ( $_GET ['val'] );

					$accounts = new SimpleXMLElement ( $api->getAccountsData ( 'accounts' ) );
					$accounts = $accounts->analyticsAccounts->account;
					$accountId = $accounts [$i]->accountId;

					echo $api->deleteAnalyticsAccount ( $accountId );
				}
				break;
			case 'forgotPassword' :
				if (isset ( $_GET ['user_name'] ))
					if (trim ( $_GET ['user_name'] ) == "") {
					} else {
						echo $api->forgotPassword ( $_GET ['user_name'] );
					}
				break;

            case "setExpiredKeyByID":
                if ( isset( $_GET['id'] )) 
                {
                    $id = $_GET['id'];
                    $found = false;

                    $accounts_obj = new SimpleXMLElement( $api->getAccountsData('accounts') );
                    $accounts = $accounts_obj->analyticsAccounts->account;

                    for ( $i = 0; $i < count( $accounts ); $i++ )
                    {
                    	if ( $id == $accounts[$i]->accountId && !$found )
                    	{
                    		$account = $accounts[$i];
                    		$found = true;

			                $data = new stdClass ();
			                
			                $data->nickName = (string) $account->nickName;
			                $data->userId = (string) $account->userId;
			                $data->accountId = (string) $account->accountId;

		                    switch ( $account->type )
		                    {
                                case "GooglePlus":
                                    $data->type = "googleplus";
                                    $api->addGaAccount( null, $data );
                                    break;
		                        case "GoogleAnalytics":
		                            $data->type = "google";
		                            $api->addGaAccount( null, $data );
		                            break;
		                        case "Facebook":
		                            $api->setExpiredKey( $data->nickName, $data->userId, false );
		                            break;
		                        case "Linkedin":
		                            $api->addLnAccount( null, $data );
		                            break;
		                        case "Twitter":
		                            $api->addTwAccount( $data->nickName, $data );
		                            break;
		                        case "Instagram":
		                        	echo "<script>window.close();</script>";
		                            break;
								case 'YouTube':
		                            $data->type = "youtube";
		                            $_COOKIE['type'] = "youtube";
		                            $api->addGaAccount ( null, $data );
		                            break;
		                        default :
		                            echo "/";
		                            break;
		                    }
                    	}
                    }
                }

                else echo "Error: Please include account ID";

                break;

            case "setExpiredKey":
                if (isset ( $_GET ['id'] )) {
                    $i = intval ( $_GET ['id'] );
                    $accounts = new SimpleXMLElement ( $api->getAccountsData ( 'accounts' ) );
                    $accounts = $accounts->analyticsAccounts->account;
                    $data = new stdClass ();
                    $data->nickName = ( string ) $accounts [$i]->nickName;
                    $data->userId = ( string ) $accounts [$i]->userId;
                    $data->accountId = ( string ) $accounts [$i]->accountId;
                    switch ($accounts [$i]->type) {
                        case "GoogleAnalytics" :
                            $data->type = "google";
                            $api->addGaAccount ( null, $data );
                            break;
                        case "Facebook" :
                            $api->setExpiredKey ( $data->nickName, $data->userId, false );
                            break;
                        case "Linkedin" :
                            $api->addLnAccount ( null, $data );
                            break;
                        case "Twitter" :
                            $api->addTwAccount ( $data->nickName, $data );
                            break;
						case 'YouTube' :
                            $data->type = "youtube";
                            $_COOKIE['type'] = "youtube";
                            $api->addGaAccount ( null, $data );
                            break;
                        default :
                            echo "/";
                            break;
                    }
                }
                break;

			default :
				break;
		endswitch;
	}
} elseif ($_SERVER ['REQUEST_METHOD'] == "POST") {

    $ajax_label = 'POST:action:' . $_POST ['action'] . ' by ' . (!empty($_SESSION['user_name']) ? $_SESSION['user_name'] : 'not logged in');
    ECErrorHandler::AJAXLog('Request for ' . $ajax_label, '', $bt);
    
    if (isset ( $_POST ['action'] )) {
		switch ($_POST ['action']) :
			case 'login' :
				if (! isset ( $_SESSION ['AUTH_STATUS'] )) {
					if (isset ( $_POST ['email'] ) && trim ( $_POST ['email'] ) != '' && isset ( $_POST ['password'] ) && trim ( $_POST ['password'] )) {
                        if (isset($_POST['internal'])){
							if($_POST['internalView']=="marketing"){
								$r = $api->internal_marketing_login ( $_POST ['email'], $_POST ['password'] );
							}
							else {
								$r = $api->internal_login ( $_POST ['email'], $_POST ['password'] );
							}
                            if ($r->returnCode == "SUCCESS") {
                                $_SESSION ['AUTH_STATUS'] = true;
                                $_SESSION ['user_name'] = trim ( str_replace ( " ", "", $_POST ['email'] ) );
                                $_SESSION ['user_pass'] = $_POST ['password'];
                                $_SESSION ['version'] = VERSION;
                                $_SESSION ['internal'] = true;
                            }
                        }else{
                            $r = $api->login ( $_POST ['email'], $_POST ['password'] );
                            if ($r->returnCode == "SUCCESS") {
                                $_SESSION ['AUTH_STATUS'] = true;
                                $_SESSION ['user_name'] = trim ( str_replace ( " ", "", $_POST ['email'] ) );
                                $_SESSION ['user_pass'] = $_POST ['password'];
                                $_SESSION ['version'] = VERSION;
                                $_SESSION ['internal'] = false;
                                if ($r->settings->licenseType == 'Individual' || $r->settings->licenseType == 'Basic' || $r->settings->licenseType == 'Premier') {
                                    $paymentConfig = $api->getUserPaymentConfig ($_SESSION ['user_name'], SERVER_IDENTIFIER );
                                    if ($paymentConfig != null){
                                        $paymentId = $paymentConfig->paymentId;
                                        try {
                                            $subscription = Recurly_Subscription::get ( $paymentId );
                                            if ($subscription->state != 'active') {
                                                //$api->updateUserPaymentConfig ( SERVER_IDENTIFIER, $r->settings->licenseType, $_SESSION ['user_name'], $subscription->state );
                                                $api->updateUserPaymentConfig ( SERVER_IDENTIFIER, 'Free', $_SESSION ['user_name'], $subscription->state );
                                                $api->setUserLicense ( $_SESSION ['user_name'], SERVER_IDENTIFIER );
                                                $r->settings->licenseType = 'Free';
                                            }
                                        } catch ( Exception $e ) {
                                        }
                                    }
                                }
                                setcookie ( "first_login", "true" );
                            }
                        }
                        $resp = array ();
                        $resp ["code"] = ( string ) $r->returnCode;
                        $resp ["description"] = ( string ) $r->description;
                        echo json_encode ( $resp );

					}
				} else {
					$resp = array ();
					$resp ["code"] = "reload";
					echo json_encode ( $resp );
                }
				break;

            case 'get_rss':
                
                if ( isset( $_POST['url'] ) )
                {
                    $url = $_POST['url'];

                    try {
                        $xml = simplexml_load_file( $url, null, LIBXML_NOCDATA );
                        // $html = file_get_contents( $_POST['url'] );

                        if ( empty( $xml ) )
                        {
                            $target_url = $url;
                            $userAgent = "ScraperBot";
                            $qw = curl_init();
                            curl_setopt( $qw, CURLOPT_USERAGENT, $userAgent );
                            curl_setopt( $qw, CURLOPT_URL, $target_url );
                            curl_setopt( $qw, CURLOPT_FAILONERROR, true );
                            curl_setopt( $qw, CURLOPT_FOLLOWLOCATION, true );
                            curl_setopt( $qw, CURLOPT_AUTOREFERER, true );
                            curl_setopt( $qw, CURLOPT_RETURNTRANSFER, true );
                            curl_setopt( $qw, CURLOPT_TIMEOUT, 20 );

                            $response = curl_exec( $qw );

                            // ECErrorHandler::rss("\n\n RSS CURL RESPONSE " .$response. "\n ======================/CURL \n");

                            // $html = file_get_html( $response );
                            $html = new simple_html_dom();

                            $html->load( $response );

                            $rss_links = $html->find('head link[type="application/rss+xml"]');

                            // ECErrorHandler::rss_logg("\n\n\nRSS LOGGING HTML". print_r( count( $rss_links ), true ) );

                            $resp = array();

                            $resp[ 0 ] = 'links';

                            $count = 1;

                            foreach ( $rss_links as $link ) 
                            {
                                $resp[ $count ]['link'] = $link->href;
                                $resp[ $count ]['title'] = $link->title;

                                $count++;
                            }

                            $json = json_encode( $resp );
                        }

                        else 
                        {
                            // ECErrorHandler::rss_logg("\n\n\nRSS LOGGING raw xml". (string) $xml );
                            // ECErrorHandler::rss_logg("\n\n\nRSS LOGGING empty?". empty( $xml ) );
                            // ECErrorHandler::rss_logg("\n\n\nRSS LOGGING xml". print_r( $xml, true ) );

                            $json = json_encode( $xml );
                        }

                        echo $json;
                    }

                    catch ( Exception $e ) {
                        echo "error";
                    }
                }

                else echo 'error';

                break;

            case 'shorten_url':
                
                if ( isset( $_POST['url'] ) )
                {
                    $googer = new GoogleURLAPI();

                    $shortDWName = $googer->shorten( $_POST['url'] );

                    echo ($shortDWName)?$shortDWName:'error'; // returns http://goo.gl/i002
                }

                else echo 'error';

                break;

            case 'url_info':
                
                if ( isset( $_POST['url'] ) )
                {
                    echo $_POST['url'];

                    $googer = new GoogleURLAPI();

                    $longDWName = $googer->analytics( $_POST['url'] ) ;
                    
                    echo $longDWName;
                } 

                else echo 'error';

                break;

            case 'sort_fallback':
                
                if (isset ( $_POST['data'] )) {} 

                else echo 'error';

                break;

            case 'publishWidget':
                if (isset ( $_POST['data'] )) 
                {
                    // echo $_POST['data']['wix_id'];
                    echo $api->setAnalyticsAccounts( $_POST['data'], 'editor', true); // 2nd param: string, 3rd param: publish?

                    MultiCache::cache_delete('ApiHandler:getWidgetFeeds:' .$_POST['data']['wix_id'] ); //cache by wix id
                } 

                else echo 'error';

                break;

			case 'setEditorMonitored':
				if (isset ( $_POST['data'] )) 
				{
                    // MultiCache::cache_delete('ApiHandler:getWidgetFeeds:' . $_POST['data']['wix_id'] ); //cache by wix id
					echo $api->setAnalyticsAccounts( $_POST['data'], 'editor'); // 2nd param: string
				} 

				else echo 'error';

				break;

            case 'showhideSave':
                if ( isset( $_POST['data'] ) ) echo $api->setAnalyticsAccounts( $_POST['data'] );
                
                else echo 'error';
                
                break;

            case 'setSingleProfileMonitored':

                if ( isset( $_POST['data'] ) ) echo $api->setAnalyticsAccounts( $_POST['data'], 'single' ); // 2nd param: string

                else echo 'error';
                
                break;

            case 'setSelectedAccounts':

                if ( isset( $_POST['data'] ) ) echo $api->setAnalyticsAccountsByID( $_POST['data'] );

                else if ( isset( $_POST['action'] ) && !isset( $_POST['data'] ) ) echo $api->setAnalyticsAccountsByID( array() );
                
                else echo 'error';
                
                break;

			case 'renameSelectedAccount':

				if ( isset( $_POST['data'] ) ) echo $api->renameAnalyticsAccountByID( $_POST['account'] );
				
                break;

			case 'editAccount' :
				echo "set";
				if (isset ( $_POST ['item'] ) && isset ( $_POST ['nickname'] )) {
					$i = intval ( $_POST ['item'] );
					$accounts = new SimpleXMLElement ( $api->getAccountsData ( 'accounts' ) );
					$accounts = $accounts->analyticsAccounts->account;
					$accountId = $accounts [$i]->accountId;
					echo $api->renameAnalyticsAccount ( $accountId, $_POST ['nickname'] );
				}
				break;
                
            case 'addContentCuration':

                if ( isset( $_POST['url'] ) )
                {
                    if ( $_POST['is_editor'] == 'true') $resp = $api->addContentCuration( $_POST['url'], $_POST['display_name'], $_POST['type'], 'true' );

                    else $resp = $api->addContentCuration( $_POST['url'], $_POST['display_name'], $_POST['type'] );

                    echo json_encode( $resp );
                }

                else echo 'error';

                break;
                
            case 'getContentCurations':

                if ( $_POST['is_editor'] == 'true') $content = $api->getContentCurations( 'true' );

                else $content = $api->getContentCurations();

                echo json_encode( $content );

                break;

            case 'removeContentCuration':

                if ( isset( $_POST['stream_id'] ) )
                {
                    $resp = $api->removeContentCuration( $_POST['stream_id'], $_POST['wix_id'] );

                    // echo $resp;
                    echo json_encode( $resp );
                }

                else echo 'error';

                break;

            case 'editContentCuration':

                if ( isset( $_POST['id'] ) && isset( $_POST['url'] ) )
                {
                    if ( $_POST['is_editor'] == 'true') 
                    {
                        $resp = $api->editContentCuration( $_POST['id'], $_POST['url'], $_POST['editor_monitored'], $_POST['published'], 'true', $_POST['wix_id'] );
                    }

                    else $resp = $api->editContentCuration( $_POST['id'], $_POST['url'], $_POST['editor_monitored'], $_POST['published'] );

                    // echo $resp;
                    echo json_encode( $resp );
                }

                else echo 'error';

                break;

            case 'getSettings':

                $settings = $api->getSettings()->settings;

                if ( ! empty( $_SESSION['client_key'] ) ) $client = $_SESSION['client_key']['clientId'];

                else $client = false;

                $settings->apiUser = $client;

                $settings->accountCode = $_SESSION['accountCode'];

                echo json_encode( $settings );
                // echo json_encode( $api->getSettings ()->settings );
                break;

            case 'getAllSettings' :
                echo json_encode( $api->getSettings() );
                break;

			case 'saveSettings':
				if ( isset( $_POST['data'] ) ) 
                {
					$settings = $api->getSettings()->settings;

                    if ( isset( $settings->actionDefinition ) )
                    {
                        $settings->actionDefinition->sendEmail = $_POST['data']['receive_email'];

                        if ( isset( $_POST['data']['sendPostFailureEmail'] ) ) $settings->actionDefinition->sendPostFailureEmail = $_POST['data']['sendPostFailureEmail'];
                        
                        if ( isset( $_POST['data']['new_pass'] ) ) $settings->newPassword = $_POST['data']['new_pass'];
                        
                        if ( isset( $_POST['data']['sort'] ) ) $settings->sortAccounts = $_POST['data']['sort'];

                        if ( isset( $_POST['data']['hideInboxEventCounter'] ) ) $settings->hideInboxEventCounter = $_POST['data']['hideInboxEventCounter'];

                        if ( isset( $_POST['data']['alwaysHideCompletedEvents'] ) ) $settings->alwaysHideCompletedEvents = $_POST['data']['alwaysHideCompletedEvents'];

                        if ( isset( $_POST['data']['displayInboxSettings'] ) ) $settings->displayInboxSettings = $_POST['data']['displayInboxSettings'];
                        
                        echo $api->setSettings( $settings );
                    }

                    else {
                        echo "Something wrong.";
                    }
                }
			break;

			case 'postMessage' :
				if (isset ( $_POST ['data'] )) {
					if (isset ( $_POST ['data'] ['mas'] )) {
						$accounts = new SimpleXMLElement ( $api->getAccountsData ( 'accounts' ) );
						$accounts = $accounts->analyticsAccounts->account;
						$response = new stdClass ();
						foreach ( $_POST ['data'] ['mas'] as $key ) {
							$i = intval ( $key ['i'] );
							if ($key ['type'] == 'Twitter') {
								$tw_account = new stdClass ();
								$tw_account->access_token = $accounts [$i]->userAccessToken;
								$tw_account->access_token_secret = $accounts [$i]->accessTokenSecret;
								$tw_account->userId = $accounts [$i]->userId;
								$response->tw->val [$i] = $api->twitter_publish ( $tw_account, $_POST ['data'] ['message'] );
							} elseif ($key ['type'] == 'Facebook') {
								$fb_account = new stdClass ();
								$fb_account->id = $accounts [$i]->userId;
								// $fb_account->token=$accounts[$i]->userAccessToken;
								$response->fb->val [$i] = $api->facebook_publish ( $fb_account, $_POST ['data'] ['message'] );
							}
						}
						echo json_encode ( $response );
					}
				}
				break;
			
            case 'getNewsFeed':
                // if (isset ( $_POST ["i"] ) && isset ( $_POST ['j'] ) && isset ( $_POST ['limit'] ))
				if ( isset( $_POST["accountID"] ) && isset( $_POST['profileID'] ) && isset( $_POST['limit'] ) )
                {
                    // $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
					$acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );

					if ( $acc_data == 'reload') echo "Reload page";
                    
                    else
                    {
						$token = $acc_data->user_at;
						$id = $acc_data->userId;
						$get_type = "home";
						
                        if ( isset( $_POST['wall'] ) && $_POST['wall'] == "true")
                        {
							$token = $acc_data->page_at;
							$id = $acc_data->pageId;
							$get_type = "feed";
						}
						
                        if (Server_Side)
                        {
							echo $api->getNewsFeedMostRecentServer( $acc_data->accountId, $acc_data->sampleId, $_POST ['stream'], "", "" );
						}
                        
                        else echo $api->getNewsFeedMostRecent( $token, $id, $get_type, $_POST ['limit'] );
					}
				} 

                else echo "Incorrect data!";

				break;

			case "doFbRequest":
				if (isset ( $_POST ["i"] ) && isset ( $_POST ['j'] ) && isset ( $_POST ['next'] ))
                {
					$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );

					if ($acc_data == 'reload') echo "Reload page";

                    else
                    {
						$token = $acc_data->user_at;
						$id = $acc_data->userId;
						$get_type = "home";
						
                        if (isset ( $_POST ['wall'] ) && $_POST ['wall'] == "true")
                        {
							$token = $acc_data->page_at;
							$id = $acc_data->pageId;
							$get_type = "feed";
						}
						// if (Server_Side) {
							echo $api->getNewsFeedMostRecentServer ( $acc_data->accountId, $acc_data->sampleId, $_POST ['stream'], $_POST ['next'], "" );
						// } else {
						// 	echo $api->doFbRequest ( $token, $id, $_POST ['next'] );
						// }
					}
				} 

                else if ( isset( $_POST["accountID"] ) && isset( $_POST['profileID'] ) && isset( $_POST['next'] ))
                {
                    $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );

                    if ($acc_data == 'reload') echo "Reload page";

                    else
                    {
                        $token = $acc_data->user_at;
                        $id = $acc_data->userId;
                        $get_type = "home";
                        
                        if (isset ( $_POST['wall'] ) && $_POST['wall'] == "true")
                        {
                            $token = $acc_data->page_at;
                            $id = $acc_data->pageId;
                            $get_type = "feed";
                        }

                        echo $api->getNewsFeedMostRecentServer( $acc_data->accountId, $acc_data->sampleId, $_POST['stream'], $_POST['next'], "" );
                    }
                }

                else echo "Incorrect data!";

				break;
			case 'getPhotosFeed':
                // if (isset ( $_POST ["i"] ) && isset ( $_POST ['j'] ) && isset ( $_POST ['next'] )) {
				if ( isset( $_POST["accountID"] ) && isset( $_POST['profileID'] ) && isset( $_POST['next'] ))
                {
                    // $acc_data = $api->getAccountData( $_POST['i'], $_POST['j'] );
                    $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
					
                    if ( $acc_data == 'reload') echo "Reload page";
                    else
                    {
						$token = $acc_data->user_at;
						$id = $acc_data->userId;
						
                        if ( Server_Side )
                        {
							echo $api->getNewsFeedMostRecentServer( $acc_data->accountId, $acc_data->sampleId, $_POST['stream'], $_POST['next'], "");
						} 
                        else echo $api->doFbRequest( $token, $id, $_POST['next'] );
					}
				} 
                else echo "Incorrect data!";

				break;
				//START LINKEDIN SECTION
			case 'getLNLikes' :
				if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] ) && isset ( $_POST ['post_id'] )) {
                    if (!$api->ifUserLogined()) {
                        echo "Reload page";
                    } else {
                        echo $api->getSocialStreamsCommand ( "likes", 'objectId', $_POST ["post_id"] );
                    }
                }

                else if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) && isset( $_POST['post_id'] ) )
                {
                    // $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );

                    // if ($acc_data == 'reload') echo "Reload page";

                    // else {
                        echo $api->getSocialStreamsCommandID( $_POST['accountID'], $_POST['profileID'], "likes", 'objectId', $_POST["post_id"] );
                    // }    
                } else {
                    echo "Incorrect data";
                }
				break;

            case 'getLNPostLikes' :
                if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) && isset( $_POST['post_id'] ) )
                {
                    // $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );

                    // if ($acc_data == 'reload') echo "Reload page";

                    // else {
                        echo $api->getSocialStreamsCommandID( $_POST['accountID'], $_POST['profileID'], "post_likes", 'objectId', $_POST["post_id"] );
                    // }    
                } else {
                    echo "Incorrect data";
                }
                break;

            case 'getLNPostComments' :

                if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) && isset( $_POST['post_id'] ) )
                {
                    // $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );

                    // if ($acc_data == 'reload') echo "Reload page";

                    // else {
                        echo $api->getSocialStreamsCommandID( $_POST['accountID'], $_POST['profileID'], "post_comments", 'objectId', $_POST["post_id"] );
                    // }

                } else {
                    echo "Incorrect data";
                }
                break;
                    	
			case 'getLNComments' :

				if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] ) && isset ( $_POST ['post_id'] )) {
                    if (!$api->ifUserLogined()) {
                        echo "Reload page";
                    } else {
                        echo $api->getSocialStreamsCommand ( "comments", 'objectId', $_POST ["post_id"] );
                    }
                }

                else if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) && isset( $_POST['post_id'] ) )
                {
                    // $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );

                    // if ($acc_data == 'reload') echo "Reload page";

                    // else {
                        echo $api->getSocialStreamsCommandID( $_POST['accountID'], $_POST['profileID'], "comments", 'objectId', $_POST["post_id"] );
                    // }

                } else {
                    echo "Incorrect data";
                }
				break;
			case 'getLNSingleCmpUpdate':

				if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] ) && isset ( $_POST ['post_id'] )) {
					if (!$api->ifUserLogined()) {
						echo "Reload page";
					} else {
						echo $api->getSocialStreamsCommand ( "cmp_update", 'objectId', $_POST ["post_id"] );
					}
                } 

                else if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) && isset( $_POST['post_id'] ) )
                {
                    // $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );

                    // if ($acc_data == 'reload') echo "Reload page";

                    // else {
                        echo $api->getSocialStreamsCommandID( $_POST['accountID'], $_POST['profileID'], "cmp_update", 'objectId', $_POST["post_id"] );
                    // }
				} 

                else echo "Incorrect data";

				break;
			
            case 'getLNContacts':
                // $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
				$acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
				
                if ($acc_data == 'reload') echo "Reload page";
                
                else
                {
					echo $api->getLNContactsFromServer( $acc_data->userId, $acc_data->accountId, $acc_data->user_at, $acc_data->user_ts, $acc_data->sampleId );
				}

				break;

			case 'getLNHome':
                // $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
				$acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );

				if ( $acc_data == 'reload') echo "Reload page";
                else
                {
						echo $api->getLNHomeFeedFromServer( $acc_data->userId, $acc_data->accountId, $acc_data->user_at, $acc_data->user_ts, $acc_data->sampleId );
				}
				break;
			
            case 'getLNGroups':
                // $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
				$acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );

				if ($acc_data == 'reload') echo "Reload page";
				else 
                {
					echo $api->getLNGroupFeedFromServer( $acc_data->userId, $acc_data->accountId, $acc_data->user_at, $acc_data->user_ts, $acc_data->sampleId );
				}
				break;
			
            case 'sendLNComment':

				//$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
			    if (!$api->ifUserLogined()) echo "Reload page";
                
                else if ( isset( $_POST["post_id"] ) && isset( $_POST['accountID'] ) && isset( $_POST["profileID"] ))
                {
					if(Server_Side){
						if (isset ( $_POST ['group'] )) {
							$message = "<?xml version='1.0' encoding='UTF-8'?><comment><text>" . $_POST ['comment'] . "</text></comment>";
							$re = $api->publishSocialStreamObject("sendLNCommentGroup",$_POST ['post_id'],$message,"comment");
						}else{
							$message = "<?xml version='1.0' encoding='UTF-8'?><update-comment><comment>" . $_POST ['comment'] . "</comment></update-comment>";
							$re = $api->publishSocialStreamObject("sendLNComment",$_POST ['post_id'],$message,"comment");
						}
						$resp = json_decode($re);
						if (isset($resp->error)) {
							echo $resp->error->streamEntry->message;
						} elseif ($resp->returnCode=="SUCCESS") {
							echo "SUCCESS";
						} else {
							echo "UNKNOWN_ERROR";
						}
					}else{
						$oauth = new OAuth ( LINKEDIN_APP_KEY, LINKEDIN_SECRET_KEY );
						$oauth->setToken ( $acc_data->user_at, $acc_data->user_ts );

						if (isset ( $_POST ['group'] )) {
							$api_url = "http://api.linkedin.com/v1/posts/" . $_POST ['post_id'] . "/comments";
							$oauth->fetch ( $api_url, "<?xml version='1.0' encoding='UTF-8'?><comment><text>" . $_POST ['comment'] . "</text></comment>", OAUTH_HTTP_METHOD_POST, array (
									'Content-Type' => 'application/xml'
							) );
						} else {
							$api_url = "http://api.linkedin.com/v1/people/~/network/updates/key=" . $_POST ['post_id'] . "/update-comments";
							$oauth->fetch ( $api_url, "<?xml version='1.0' encoding='UTF-8'?><update-comment><comment>" . $_POST ['comment'] . "</comment></update-comment>", OAUTH_HTTP_METHOD_POST, array (
									'Content-Type' => 'application/xml'
							) );
						}
						// $response = $oauth->getLastResponse(); // just a sample of how you would get the response

						$re = $oauth->getLastResponseInfo ();
						if ($re ['http_code'] == 201) {
							echo "SUCCESS";
						} else {
							echo "UNKNOWN_ERROR";
						}
					}
				}
                else
                {
                    echo 'Incorrect data!';
                }

				break;
			case 'sendLNLike' :
                if (!$api->ifUserLogined()) echo "Reload page";

                else
                {
				//$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
				//if ($acc_data == 'reload') {
				//	echo "Reload page";
				//} else {
				//	if(Server_Side){
						$is_liked = "<?xml version='1.0' encoding='UTF-8'?><is-liked>" . $_POST ['like'] . "</is-liked>";
						if (isset ( $_POST ['group'] )){
							$re = $api->publishSocialStreamObject("sendLNLikeGroup",$_POST ['post_id'],$is_liked,"is-liked");
						} else {
							$re = $api->publishSocialStreamObject("sendLNLike",$_POST ['post_id'],$is_liked,"is-liked");
						}
						$resp = json_decode($re);
						if (isset($resp->error)) {
							echo $resp->error->streamEntry->message;
						} elseif ($resp->returnCode=="SUCCESS") {
							echo "SUCCESS";
						} else {
							echo "UNKNOWN_ERROR";
						}
					/*} else {
						$oauth = new OAuth ( LINKEDIN_APP_KEY, LINKEDIN_SECRET_KEY );
						$oauth->setToken ( $acc_data->user_at, $acc_data->user_ts );
						if (isset ( $_POST ['group'] )) {
							$api_url = "http://api.linkedin.com/v1/posts/" . $_POST ['post_id'] . "/relation-to-viewer/is-liked";
						} else {
							$api_url = "http://api.linkedin.com/v1/people/~/network/updates/key=" . $_POST ['post_id'] . "/is-liked";
						}
						$oauth->fetch ( $api_url, "<?xml version='1.0' encoding='UTF-8'?><is-liked>" . $_POST ['like'] . "</is-liked>", OAUTH_HTTP_METHOD_PUT, array (
								'Content-Type' => 'application/xml'
						) );

						// $response = $oauth->getLastResponse(); // just a sample of how you would get the response

						$re = $oauth->getLastResponseInfo ();
						if ($re ['http_code'] == 201) {
							echo "SUCCESS";
						} else {
							echo "UNKNOWN_ERROR";
						}
					}*/
				}

				break;
			case 'sendLNFollow' :
				// $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
				// if ($acc_data == 'reload') {
				// 	echo "Reload page";
				// } else {
					if(Server_Side){
						$is_following = "<?xml version='1.0' encoding='UTF-8'?><is-following>" . $_POST ['follow'] . "</is-following>";
						$re = $api->publishSocialStreamObject("sendLNFollow",$_POST ['post_id'],$is_following,"is-following");
						$resp = json_decode($re);
						if (isset($resp->error)) {
							echo $resp->error->streamEntry->message;
						} elseif ($resp->returnCode=="SUCCESS") {
							echo "SUCCESS";
						} else {
							echo "UNKNOWN_ERROR";
						}
					} else {
						$oauth = new OAuth ( LINKEDIN_APP_KEY, LINKEDIN_SECRET_KEY );
						$oauth->setToken ( $acc_data->user_at, $acc_data->user_ts );
						$api_url = "http://api.linkedin.com/v1/posts/" . $_POST ['post_id'] . "/relation-to-viewer/is-following";
						$oauth->fetch ( $api_url, "<?xml version='1.0' encoding='UTF-8'?><is-following>" . $_POST ['follow'] . "</is-following>", OAUTH_HTTP_METHOD_PUT, array (
								'Content-Type' => 'application/xml'
						) );
						$re = $oauth->getLastResponseInfo ();
						if ($re ['http_code'] == 204) {
							echo "SUCCESS";
						} else {
							echo "UNKNOWN_ERROR";
						}
					}
				// }

				break;
			case 'sendLNMessage' :
				//$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
                $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );

				if ($acc_data == 'reload') {
					echo "Reload page";
				} else {
					//if(Server_Side){
						$re = $api->publishSocialStreamObject("sendLNMessage","",$_POST ['request'],"mailbox-item");
						$resp = json_decode($re);
						if (isset($resp->error)) {
							echo $resp->error->streamEntry->message;
						} elseif ($resp->returnCode=="SUCCESS") {
							echo "SUCCESS";
						} else {
							echo "UNKNOWN_ERROR";
						}
					// } else {
					// 	$oauth = new OAuth ( LINKEDIN_APP_KEY, LINKEDIN_SECRET_KEY );
					// 	$oauth->setToken ( $acc_data->user_at, $acc_data->user_ts );

					// 	$api_url = "http://api.linkedin.com/v1/people/~/mailbox";
					// 	$oauth->fetch ( $api_url, $_POST ['request'], OAUTH_HTTP_METHOD_POST, array (
					// 			'Content-Type' => 'application/json'
					// 	) );
					// 	$response = $oauth->getLastResponse (); // just a sample of how you would get the response

					// 	$re = $oauth->getLastResponseInfo ();
					// 	if ($re ['http_code'] == 201) {
					// 		echo "SUCCESS";
					// 	} else {
					// 		echo "UNKNOWN_ERROR";
					// 	}
					// }
				}

				break;
			case 'LNShareAnUpdate' :
				$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
				if ($acc_data == 'reload') {
					echo "Reload page";
				} else {
					$re = $api->publishSocialStreamObject("LNShareAnUpdate","",$_POST ['share'],"share");
					$resp = json_decode($re);
					if (isset($resp->error)) {
						echo $resp->error->streamEntry->message;
					} elseif ($resp->returnCode=="SUCCESS") {
						echo "SUCCESS";
					} else {
						echo "UNKNOWN_ERROR";
					}
				}
				break;	
			case 'LNStatusUpdate' :
				$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
				if ($acc_data == 'reload') {
					echo "Reload page";
				} else {
					if(Server_Side){
						$status = '<?xml version="1.0" encoding="UTF-8"?><current-status>' . $_POST ['request'] . '</current-status>';
						$re = $api->publishSocialStreamObject("LNStatusUpdate","",$status,"status");
						$resp = json_decode($re);
						if (isset($resp->error)) {
							echo $resp->error->streamEntry->message;
						} elseif ($resp->returnCode=="SUCCESS") {
							echo "SUCCESS";
						} else {
							echo "UNKNOWN_ERROR";
						}
					}else{
						$oauth = new OAuth ( LINKEDIN_APP_KEY, LINKEDIN_SECRET_KEY );
						$oauth->setToken ( $acc_data->user_at, $acc_data->user_ts );
						// if (isset($_POST['profile_Id'])){
						// $api_url = "http://api.linkedin.com/v1/companies/".$_POST['profile_Id']."/shares";
						// // $api_url = "http://api.linkedin.com/v1/companies/2414183/is-company-share-enabled";
						// // $oauth->fetch($api_url, '<?xml version="1.0" encoding="UTF-8"?.><share><visibility><code>anyone</code></visibility><comment>'.$_POST['request'].'</comment></share>', OAUTH_HTTP_METHOD_POST, array('Content-Type' => 'application/xml'));
						// try{
						// $body = new stdClass();
						// $body->comment = "test message";
						// $body->visibility = new stdClass();
						// $body->visibility->code = "anyone";
						// $body_json = json_encode($body);
						// $oauth->fetch($api_url, $body_json, OAUTH_HTTP_METHOD_POST, array("Content-Type" => "application/json","x-li-format" => "json"));;
						// // $oauth->fetch($api_url, null, OAUTH_HTTP_METHOD_GET, array("Content-Type" => "application/json","x-li-format" => "json"));
						// }catch (Exception $e){
						// echo $e;
						// // echo $acc_data->user_at."___";
						// // echo $acc_data->user_ts;
						// }
						//
						// }else{
						$api_url = "http://api.linkedin.com/v1/people/~/current-status";
						$oauth->fetch ( $api_url, '<?xml version="1.0" encoding="UTF-8"?><current-status>' . $_POST ['request'] . '</current-status>', OAUTH_HTTP_METHOD_PUT, array (
													'Content-Type' => 'application/xml'
						) );
						// }
						$response = $oauth->getLastResponse (); // just a sample of how you would get the response

						$re = $oauth->getLastResponseInfo ();
						// echo $response;
						// echo json_encode($re);
						if ($re ['http_code'] == 204) {
							echo "SUCCESS";
						} else {
							echo "UNKNOWN_ERROR";
						}
					}
				}

				break;
				//TODO where is this used?
			case 'getLNCMP' :
				$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
				if ($acc_data == 'reload') {
					echo "Reload page";
				} else {
					$oauth = new OAuth ( LINKEDIN_APP_KEY, LINKEDIN_SECRET_KEY );
					$oauth->setToken ( $acc_data->user_at, $acc_data->user_ts );

					$api_url = "https://api.linkedin.com/v1/companies?is-company-admin=true";

					$oauth->fetch ( $api_url, null, OAUTH_HTTP_METHOD_GET, array (
							'x-li-format' => 'json'
					) );
					$response = $oauth->getLastResponse (); // just a sample of how you would get the response

					$response = json_decode ( $response );
					if (isset ( $response->_total ) && $response->_total > 0) {
						foreach ( $response->values as $item ) {
							echo $item->id . '+' . $item->name;
						}
					}
					// echo $response;
				}
				break;
				//TODO where is this used?
			case 'getLNGroupPosts' :
                // $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
				$acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
				if ($acc_data == 'reload') {
					echo "Reload page";
				} else {
					if(Server_Side){

						echo $api->getLNGroupPostsFromServer($_POST ['groupId'], $acc_data->accountId, $acc_data->sampleId );

					} else {
						$oauth = new OAuth ( LINKEDIN_APP_KEY, LINKEDIN_SECRET_KEY );
						$oauth->setToken ( $acc_data->user_at, $acc_data->user_ts );

						$api_url = "http://api.linkedin.com/v1/groups/" . $_POST ['groupId'] . "/posts:(creation-timestamp,title,summary,creator:(first-name,last-name,picture-url,id),likes,comments,id,attachment:(image-url,content-domain,content-url,title,summary),relation-to-viewer,type)?category=discussion&order=recency&count=5";
						// $api_url = "http://api.linkedin.com/v1/groups/".$_POST['groupId']."/posts";
						$oauth->fetch ( $api_url, null, OAUTH_HTTP_METHOD_GET, array (
								'x-li-format' => 'json'
						) );
						$response = $oauth->getLastResponse (); // just a sample of how you would get the response

						echo $response;
					}
				}
				break;
			
            case 'getLNInbox':
                // $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
				$acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
				
                if ($acc_data == 'reload') echo "Reload page";
                else 
                {
					echo $api->getLNMyUpdatesInBoxFeedFromServer( $acc_data->userId, $acc_data->accountId, $acc_data->user_at, $acc_data->user_ts, $acc_data->sampleId );
				}
				break;
			
            case 'getLNCompanies':
                // $acc_data = $api->getAccountData( $_POST ['i'], $_POST ['j'] );
				$acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
				
                if ($acc_data == 'reload') echo "Reload page";
                else
                {
					echo $api->getLNCompaniesFeedFromServer( $acc_data->userId, $acc_data->accountId, $acc_data->user_at, $acc_data->user_ts, $acc_data->sampleId );
				}
				break;

			case 'getLNUserProfile' :
                if ( isset( $_POST['i'] ) && isset( $_POST['j'] ) ) $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
				else if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) ) $acc_data = $api->getAccountDataByID( $_POST ['accountID'], $_POST ['profileID'] );
				if ($acc_data == 'reload') {
					echo "Reload page";
				} else {
					if(Server_Side){
						if (isset ( $_POST ['user_id'] )) {
                            $response = $api->getLNUserProfileServer ( $_POST ['user_id'], $acc_data->accountId, $acc_data->sampleId, false, false );
                        } else if (isset ( $_POST ['cmp_id'] )) {
                            $response = $api->getLNUserProfileServer ( $_POST ['cmp_id'], $acc_data->accountId, $acc_data->sampleId, false, true );
                        } else {
                            $response = $api->getLNUserProfileServer ( $acc_data->userId, $acc_data->accountId, $acc_data->sampleId, true, false );
                            $response = json_decode ( $response );
                            /*$response->i = $_POST ['i'];
                            $response->j = $_POST ['j'];*/
                            $response->type = "ln";
                            $response = json_encode ( $response );
						}
						echo $response;
					} else {
						$oauth = new OAuth ( LINKEDIN_APP_KEY, LINKEDIN_SECRET_KEY );
						$oauth->setToken ( $acc_data->user_at, $acc_data->user_ts );
						if (isset ( $_POST ['user_id'] )) {
							$api_url = "http://api.linkedin.com/v1/people/id=" . $_POST ['user_id'] . ":(first-name,last-name,location,num-connections,headline,industry,summary)";
						} else {
							$api_url = "http://api.linkedin.com/v1/people/id=" . $acc_data->userId . ":(num-connections)";
						}

						$oauth->fetch ( $api_url, null, OAUTH_HTTP_METHOD_GET, array (
								'x-li-format' => 'json'
						) );
						$response = $oauth->getLastResponse (); // just a sample of how you would get the response
						if (! isset ( $_POST ['user_id'] )) {
							$response = json_decode ( $response );
							$response->i = $_POST ['i'];
							$response->j = $_POST ['j'];
							$response->type = "ln";
							$response = json_encode ( $response );
						}
						echo $response;
					}
				}

				break;
			
            case 'getLNCmpHome':
                // $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'], $_POST ['profile_Id'] );
				$acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'], $_POST['profile_Id'] );

				if ($acc_data == 'reload') echo "Reload page";
                else 
                {
					echo $api->getLNCmpHomeFeedFromServer( $acc_data->userId, $acc_data->accountId, $acc_data->user_at, $acc_data->user_ts, $acc_data->sampleId, $_POST['profile_Id'] );
				}
				break;

			case 'getLNCmpProducts':
                // $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'], $_POST ['profile_Id'] );
				$acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'], $_POST['profile_Id'] );

				if ($acc_data == 'reload') echo "Reload page";
                else
                {
					echo $api->getLNCmpProductsFeedFromServer( $acc_data->userId, $acc_data->accountId, $acc_data->user_at, $acc_data->user_ts, $acc_data->sampleId );
				}
				break;

				// end linkedin


			case 'getTWHomeFeed':
                // $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
				$acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
				if ($acc_data == 'reload') {
					echo "Reload page";
				} else {
					echo $api->getTWHomeFeedFromServer( $acc_data->userId, $acc_data->accountId, $acc_data->user_at, $acc_data->user_ts, $acc_data->sampleId );
				}
				break;
			case 'getTWMentions':
                // $acc_data = $api->getAccountData( $_POST ['i'], $_POST ['j'] );
				$acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
				if ($acc_data == 'reload') {
					echo "Reload page";
				} else {
					echo $api->getTWMentionsFromServer( $acc_data->userId, $acc_data->accountId, $acc_data->user_at, $acc_data->user_ts, $acc_data->sampleId );
				}
				break;
			//  send direct messages (inbox/outbox)
			case 'getTWInBox':
                // $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
				$acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
				if ($acc_data == 'reload') {
					echo "Reload page";
				} else {
					//echo $api->getTWDirectMessagesInBoxFromServer( $acc_data->userId, $acc_data->accountId, $acc_data->user_at, $acc_data->user_ts );
                    echo $api->getTWDirectMessagesInBoxFromServerNew( $acc_data->userId, $acc_data->accountId, $acc_data->user_at, $acc_data->user_ts );
				}
				break;

			case 'getTWUserProfile':
				//$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
                $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );

				if ($acc_data == 'reload') echo "Reload page";

                 else 
                 {
					// if (Server_Side) {
						if ( !isset ( $_POST['user_id'] )) 
                        {
							$result = $api->getTWUserProfileServer( $acc_data->userId, $acc_data->accountId, $acc_data->sampleId );
							//$result->addChild('i', $_POST ['i']);
							//$result->addChild('j', $_POST ['j']);
							$result->addChild('type', "tw");
						} 
                        else $result = $api->getTWUserProfileServer( $_POST['user_id'], $acc_data->accountId, $acc_data->sampleId );

					// } else {
						/*$connection = new TwitterOAuth ( CONSUMER_KEY, CONSUMER_SECRET, ( string ) $acc_data->user_at, ( string ) $acc_data->user_ts );
						if (! isset ( $_POST ['user_id'] )) {
							$result = $connection->get ( 'users/show', array (
									'user_id' => ( string ) $acc_data->userId,
									'include_entities' => 'false'
							) );
							$result->i = $_POST ['i'];
							$result->j = $_POST ['j'];
							$result->type = "tw";
						} else {
							$result = $connection->get ( 'users/show', array (
									'user_id' => $_POST ['user_id']
							) );
						}*/
					// }

					echo json_encode ( $result );
				}
				break;

			case 'getSingleTweet':
				//$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
                // $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
				// if ($acc_data == 'reload') {
					// echo "Reload page";
				// } else {
					// $result = $api->getSingleTweet ( $acc_data->userId, $acc_data->accountId, $acc_data->user_at, $acc_data->user_ts );
					$result = $api->getSingleTweet( $_POST['tweet_id'], $_POST['accountID'] );

					echo json_encode ( $result );
				// }

				break;

			// TODO ?? WHERE USED?
			case 'getTWOutBox' :
				$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
				if ($acc_data == 'reload') {
					echo "Reload page";
				} else {
					$connection = new TwitterOAuth ( CONSUMER_KEY, CONSUMER_SECRET, ( string ) $acc_data->user_at, ( string ) $acc_data->user_ts );
					$array = array (
							'include_entities' => 'true'
					);
					if (isset ( $_POST ['max_id'] )) {
						$array ['max_id'] = $_POST ['max_id'];
					}
					$result = $connection->get ( 'direct_messages/sent', $array );
					echo json_encode ( $result );
				}

				break;
			case 'getTWRetweets':
                // $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
				$acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
				if ($acc_data == 'reload') {
					echo "Reload page";
				} else {
					echo $api->getTWRetweetsFromServer( $acc_data->userId, $acc_data->accountId, $acc_data->user_at, $acc_data->user_ts );
				}
				break;
			case 'getTWFavorites' :
                // $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
				$acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
				if ($acc_data == 'reload') {
					echo "Reload page";
				} else {
					echo $api->getTWFavoritesFromServer( $acc_data->userId, $acc_data->accountId, $acc_data->user_at, $acc_data->user_ts );
				}
				break;
            case 'getTWFollowers' :
                // $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
                $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
                
                if ($acc_data == 'reload') echo "Reload page";
                
                else
                {
                    echo $api->getTWFollowersFromServer( $acc_data->userId, $acc_data->accountId, $acc_data->user_at, $acc_data->user_ts );
                }
                break;    
			// added by KH 05/15/2013 to address retweets of third-party twitter posts:
			case 'getOtherRetweets' :
                // $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
				$acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
				
                if ($acc_data == 'reload') echo "Reload page";

                else 
                {
					// if (Server_Side)
                    // {
						$result = $api->getOtherRetweetsServer( $_POST["postId"], $_POST['accountID'], $_POST['profileID'] );
						echo json_encode ( $result->data );
					// }
     //                else
     //                {
					// 	$connection = new TwitterOAuth ( CONSUMER_KEY, CONSUMER_SECRET, ( string ) $acc_data->user_at, ( string ) $acc_data->user_ts );
					// 	$result = $connection->get ( 'statuses/retweets/' . $_POST ["postId"], array (
					// 			// $result = $connection->get('statuses/retweets/'.$_POST["postId"].'.json?count='.$_POST["count"], array(
					// 			'include_entities' => 'true',
					// 			'count' => '100'
					// 	) );
					// 	echo json_encode ( $result );
					// }
				}

				break;
				//MYTWEETS
			case 'getTWSendTweets':
                // $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
				$acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
				
                if ($acc_data == 'reload') echo "Reload page";

				else
                {    
                    $response = $api->getTWSendTweetsFromServer( $acc_data->userId, $acc_data->accountId, $acc_data->user_at, $acc_data->user_ts );

					echo $response;
				}

				break;

            case 'getTWLists':
                $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
                if ($acc_data == 'reload') {
                    echo "Reload page";
                } else {
                    echo $api->getTWListsFromServer( $acc_data->userId, $acc_data->accountId, $acc_data->sampleId, $acc_data->user_at, $acc_data->user_ts );
                }
                break;

            case 'getTWListFeed':
                $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
                if ($acc_data == 'reload') {
                    echo "Reload page";
                } else {
                    echo $api->getTWListFeedFromServer( $acc_data->userId, $acc_data->accountId, $acc_data->user_at, $acc_data->user_ts, $_POST['listId'] );
                }
                break;

            case 'getTWSearch':
                $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
                if ($acc_data == 'reload') {
                    echo "Reload page";
                } else {
                    echo $api->getTWSearchFromServer( $acc_data->userId, $acc_data->accountId, $acc_data->user_at, $acc_data->user_ts, $_POST['parameters'], $_POST['type'] );
                }
                break;

			case 'TWRetweet':
                // $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
				
				// if ($acc_data == 'reload') {
					// echo "Reload page";
				// } else {
					// if (Server_Side) {
						echo $api->publishSocialStreamObject( "TWRetweet", $_POST["id"], "", "" );
					// }else{
						// $connection = new TwitterOAuth ( CONSUMER_KEY, CONSUMER_SECRET, ( string ) $acc_data->user_at, ( string ) $acc_data->user_ts );
						// $result = $connection->post ( 'statuses/retweet/' . $_POST ['id'] );
						// echo json_encode ( $result );
					// }
				// }
				break;

            case 'TWUnRetweet':
            
                // $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
                
                // if ($acc_data == 'reload') {
                    // echo "Reload page";
                // } else {
                    // if (Server_Side) {
                        echo $api->publishSocialStreamObject( "TWSTDestroy", $_POST["id"], "", "" );
                    // }else{
                        // $connection = new TwitterOAuth ( CONSUMER_KEY, CONSUMER_SECRET, ( string ) $acc_data->user_at, ( string ) $acc_data->user_ts );
                        // $result = $connection->post ( 'statuses/retweet/' . $_POST ['id'] );
                        // echo json_encode ( $result );
                    // }
                // }
                break;

			case 'TWReply':

				// $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
			
            	// if ($acc_data == 'reload')
                // {
					// echo "Reload page";
				// } 
                // else
                // {
					// if (Server_Side) {
						echo $api->publishSocialStreamObject( "TWReply", $_POST ['id'], $_POST['status'], "status" );
					// }else{
						// $connection = new TwitterOAuth ( CONSUMER_KEY, CONSUMER_SECRET, ( string ) $acc_data->user_at, ( string ) $acc_data->user_ts );
						// $result = $connection->post ( 'statuses/update', array (
								// 'in_reply_to_status_id' => $_POST ['id'],
								// 'status' => $_POST ['status']
						// ) );
						// echo json_encode ( $result );
					// }
				// }
				break;

			case 'TWDestroy' :
				/*$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
				if ($acc_data == 'reload') {
					echo "Reload page";
				} else {
					if (Server_Side) {*/
						echo $api->publishSocialStreamObject ( "TWDestroy", $_POST ['id'], "", "" );
					/*}else{
						$connection = new TwitterOAuth ( CONSUMER_KEY, CONSUMER_SECRET, ( string ) $acc_data->user_at, ( string ) $acc_data->user_ts );
						$result = $connection->post ( 'direct_messages/destroy', array (
								'id' => $_POST ['id']
						) );
						echo json_encode ( $result );
					}
				}*/
				break;
			
            case 'TWSTDestroy':
				// $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
				// if ($acc_data == 'reload') {
					// echo "Reload page";
				// } else {
					// if (Server_Side) {
						echo $api->publishSocialStreamObject( "TWSTDestroy", $_POST['id'], "", "" );
					// }else{
						// $connection = new TwitterOAuth ( CONSUMER_KEY, CONSUMER_SECRET, ( string ) $acc_data->user_at, ( string ) $acc_data->user_ts );
						// $result = $connection->post ( 'statuses/destroy/' . $_POST ['id'], array () );
						// echo json_encode ( $result );
					// }
				// }
				break;

			case 'TWWallPost' :
				// $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
				// if ($acc_data == 'reload') {
				// 	echo "Reload page";
				// } else {
				// 	if (Server_Side) {
						echo $api->publishSocialStreamObject ( "TWWallPost", "", $_POST ['status'], "status" );
				// 	}else{
				// 		$connection = new TwitterOAuth ( CONSUMER_KEY, CONSUMER_SECRET, ( string ) $acc_data->user_at, ( string ) $acc_data->user_ts );
				// 		$result = $connection->post ( 'statuses/update', array (
				// 				'status' => $_POST ['status']
				// 		) );
				// 		echo json_encode ( $result );
				// 	}
				// }
				break;
			
            case 'TWSend':
				// $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
				// if ($acc_data == 'reload') {
				// 	echo "Reload page";
				// } else {
				// 	if (Server_Side) {
						echo $api->publishSocialStreamObject( "TWSend", $_POST['user_id'], $_POST['text'], "text" );
				// 	}else{
				// 		$connection = new TwitterOAuth ( CONSUMER_KEY, CONSUMER_SECRET, ( string ) $acc_data->user_at, ( string ) $acc_data->user_ts );
				// 		$result = $connection->post ( 'direct_messages/new', array (
				// 				'user_id' => $_POST ['user_id'],
				// 				'text' => $_POST ['text']
				// 		) );
				// 		echo json_encode ( $result );
				// 	}
				// }
				break;

			case 'TWFriends' :
				if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] )) {
					$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
					if ($acc_data == 'reload') {
						echo "Reload page";
					} else {
						if(Server_Side) {
							$result = $api->getTWFriends ( $acc_data->accountId, $acc_data->sampleId );
						}else{
							$connection = new TwitterOAuth ( CONSUMER_KEY, CONSUMER_SECRET, ( string ) $acc_data->user_at, ( string ) $acc_data->user_ts );
							$array = array (
									'user_id' => ( string ) $acc_data->userId
							);
							$result = $connection->get ( 'friends/ids', $array );
						}
						echo json_encode ( $result );
					}
				} else {
					echo "Incorrect data";
				}
				break;

			case 'TWFollow':
				// if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] ) && isset ( $_POST ['user_id'] )) {
				// 	$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
				// 	if ($acc_data == 'reload') {
				// 		echo "Reload page";
				// 	} else {
				// 		if (Server_Side) {
							echo $api->publishSocialStreamObject( "TWFollow", $_POST['user_id'], 'true', "follow" );
				// 		}else{
				// 			$connection = new TwitterOAuth ( CONSUMER_KEY, CONSUMER_SECRET, ( string ) $acc_data->user_at, ( string ) $acc_data->user_ts );
				// 			$array = array (
				// 					'user_id' => $_POST ['user_id'],
				// 					'follow' => 'true'
				// 			);
				// 			$result = $connection->post ( 'friendships/create', $array );
				// 			echo json_encode ( $result );
				// 		}
				// 	}
				// } else {
				// 	echo "Incorrect data";
				// }
				break;

			case 'TWFavorite':
					// if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] ) && isset ( $_POST ['id'] )) {
					// 	$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
					// 	if ($acc_data == 'reload') {
					// 		echo "Reload page";
					// 	} else {
							echo $api->publishSocialStreamObject( "TWFavorite", $_POST['id'], "", "" );
					// 	}
					// } else {
					// 	echo "Incorrect data";
					// }
					break;
			case 'TWUnFavorite' :
					// if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] ) && isset ( $_POST ['id'] )) {
					// 	$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
					// 	if ($acc_data == 'reload') {
					// 		echo "Reload page";
					// 	} else {
							echo $api->publishSocialStreamObject ( "TWUnFavorite", $_POST['id'], "", "" );
					// 	}
					// } else {
					// 	echo "Incorrect data";
					// }
					break;
			
            case 'TWUnFollow':
				// if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] ) && isset ( $_POST ['user_id'] )) {
				// 	$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
				// 	if ($acc_data == 'reload') {
				// 		echo "Reload page";
				// 	} else {
				// 		if (Server_Side) {
							echo $api->publishSocialStreamObject( "TWUnFollow", $_POST['user_id'], "", "" );
				// 		}else{
				// 			$connection = new TwitterOAuth ( CONSUMER_KEY, CONSUMER_SECRET, ( string ) $acc_data->user_at, ( string ) $acc_data->user_ts );
				// 			$array = array (
				// 					'user_id' => $_POST ['user_id']
				// 			);
				// 			$result = $connection->delete ( 'friendships/destroy', $array );
				// 			echo json_encode ( $result );
				// 		}
				// 	}
				// } else {
				// 	echo "Incorrect data";
				// }
				break;
			
            case 'likeFBPost':

                // echo json_encode( $_POST );
                if (!$api->ifUserLogined()) echo "Reload page";
                // if (isset ( $_POST ["post_id"] ) && isset ( $_POST ["i"] ) && isset ( $_POST ['j'] )) {
				else if ( isset( $_POST["post_id"] ) && isset( $_POST['accountID'] ) && isset( $_POST['profileID'] )) {
					
                    if ($_POST['like'] == "false") {
                        echo $api->publishSocialStreamObject( "likeFBPost", $_POST ["post_id"], "", "message" );
                    } else {
                        echo $api->publishSocialStreamObject( "unlikeFBPost", $_POST ["post_id"], "", "message" );
                    }

				} else {
					echo "Incorrect data!";
				}
				break;

            case 'updateFBComment':
                
                if (!$api->ifUserLogined()) echo "Reload page";

                else if ( isset( $_POST["comment_id"] ) && isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) && isset($_POST['message']) ) {

                    echo $api->publishSocialStreamObject( "updateFBComment", $_POST ["comment_id"], $_POST['message'], "message" );

                } else {
                    echo "Incorrect data!";
                }
                break;

            case 'deleteFBComment':
                
                if (!$api->ifUserLogined()) echo "Reload page";

                else if ( isset( $_POST["comment_id"] ) && isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) ) {

                    echo $api->publishSocialStreamObject( "deleteFBComment", $_POST ["comment_id"], "", "" );

                } else {
                    echo "Incorrect data!";
                }
                break;

            case 'likeFBComment':

                // echo json_encode( $_POST );
                if (!$api->ifUserLogined()) echo "Reload page";
                // if (isset ( $_POST ["post_id"] ) && isset ( $_POST ["i"] ) && isset ( $_POST ['j'] )) {
                else if ( isset( $_POST["comment_id"] ) && isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) && isset( $_POST["liked"] ) ) {

                    if ( $_POST["liked"] == 'true' )
                        echo $api->publishSocialStreamObject( "unlikeFBComment", $_POST ["comment_id"], "", "message" );
                    else
                        echo $api->publishSocialStreamObject( "likeFBComment", $_POST ["comment_id"], "", "message" );

                } else {
                    echo "Incorrect data!";
                }
                break;

			case "addFbComment":
                
                if (!$api->ifUserLogined()) echo "Reload page";
                // if (isset ( $_POST ["post_id"] ) && isset ( $_POST ['i'] ) && isset ( $_POST ["j"] )) {
				else if ( isset( $_POST["post_id"] ) && isset( $_POST['accountID'] ) && isset( $_POST["profileID"] ))
                {
					// $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
                    // else
                    // {
					if (isset ( $_POST ['message'] ))
                    {
						// if (Server_Side) {
						echo $api->publishSocialStreamObject( "addFbComment", $_POST ["post_id"], $_POST ['message'], "message" );
						// } else {
						// 	$resp = $api->addFbComment ( $acc_data->user_at, $_POST ['post_id'], $_POST ['message'] );
						// 	echo json_encode ( $resp );
						// }
					}
                    else echo "Please enter your message";
					// }
				} else {
					echo 'Incorrect data!';
				}
				break;

			case "deletePostItem" :
				//if (isset ( $_POST ["post_id"] ) && isset ( $_POST ["i"] ) && isset ( $_POST ['j'] )) {
                if ( isset( $_POST['post_id'] ) && isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) ) {
					//$acc_data = $api->getAccountData ( $_POST ["i"], $_POST ['j'] );
                    $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );

					if ($acc_data == 'reload') {
						echo "Reload page";
					} else {
						if (Server_Side) {
							$resp = $api->publishSocialStreamObject ( "deleteFbPost", $_POST ["post_id"], "", "message" );
                            $resp = json_decode($resp);
							if (isset($resp->returnCode) && $resp->returnCode == "SUCCESS" && ! isset ( $resp->error )) {
								echo "Your post succesfully deleted!";
							} else {
								echo json_encode($resp);
							}
						} else {
							$resp = $api->deleteFbPost ( $acc_data->user_at, $_POST ['post_id'] );
							if ($resp === true) {
								echo "Your post succesfully deleted!";
							} else {
								echo $resp;
							}
						}
					}
				} else {
					echo "Incorrect data!";
				}
				break;
			
            case "addFbPost":
                // if (isset ( $_POST ['message'] ) && isset ( $_POST ['i'] ) && isset ( $_POST ['j'] ))
				if ( isset( $_POST['message'] ) && isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ))
                {
                    // $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
					$acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
					
                    if ($acc_data == 'reload') echo "Reload page";

                    else 
                    {
						// if (Server_Side) {
                            if (isset($_POST['inbox']) && $_POST['inbox'] == 'true')
                            {
                                if (isset($_POST['user']) && $_POST['user'] == 'true')
                                {
                                    echo $api->publishSocialStreamObject ( "replyFBinbox", $_POST['id'], $_POST ["message"], "message" );
                                }
                                else
                                {
                                    echo $api->publishSocialStreamObject ( "replyFBconversation", $_POST['id'], $_POST ["message"], "message" );
                                }
                            }
                            else
                            {
                                if (isset($_POST['group_id'])){
                                    echo $api->publishSocialStreamObject ( "addFbPost", $_POST['group_id'], $_POST ["message"], "message" );
                                }
                                else
                                {
                                    echo $api->publishSocialStreamObject ( "addFbPost", "", $_POST ["message"], "message" );
                                }
                            }
						// } else {
						// 	$resp = $api->addFbPost ( $acc_data->page_at, $acc_data->pageId, $_POST ["message"] );
						// 	echo json_encode ( $resp );
						// }
					}
				} 

                else echo "Incorrect data";

				break;

			case "getTWInfluencers":
                // $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
				$acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
				if ($acc_data == 'reload') {
					echo "Reload page";
				} else {
					$resp = $api->getInfluencers('tw', $acc_data->accountId );
					echo json_encode ( $resp );
				}

				break;
			
            case "getFBInfluencers":
                // $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
				$acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );

				if ( $acc_data == 'reload') echo "Reload page";

                else
                {
					$resp = $api->getInfluencers( 'fb', $acc_data->sampleId );
					
                    if ($resp->returnCode == "FAIL") echo (string) $resp->returnCode;

                    else echo (string) $resp->influenceData;
				}
				break;

			case "getFbEvents":
                // if ( isset( $_POST['i'] ) && isset( $_POST['j'] ) && isset( $_POST['next'] ))
				if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) && isset( $_POST['next'] ))
                {
					$acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
					
                    if ($acc_data == 'reload') echo "Reload page";

                    else
                    {
						if ( Server_Side ) echo $api->getNewsFeedMostRecentServer( $acc_data->accountId, $acc_data->sampleId, $_POST['stream'], $_POST['next'], ""); 
                        else
                        {
							$resp = $api->getEvents( $acc_data->page_at, $acc_data->pageId, $_POST['next'] );
							echo json_encode( $resp );
						}
					}
				} 
                else echo "Incorrect data";

				break;

			case "getFBUserProfile":
				if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] ))
                {
					$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
				
                	if ($acc_data == 'reload') echo "Reload page";

                    else {
 						if ( !isset( $_POST['user_id'] ) )
                        {
							// if(Server_Side) {
								$resp = $api->getFBUserProfileServer ( $acc_data->pageId, $acc_data->accountId, $acc_data->sampleId );
								$resp->addChild('i', $_POST ['i']);
								$resp->addChild('j', $_POST ['j']);
								$resp->addChild('type', "fb");
							// }else{
							// 	$resp = $api->getFBinfo ( $acc_data->page_at, $acc_data->pageId, $_POST ['user'] );
							// 	$resp ['i'] = $_POST ['i'];
							// 	$resp ['j'] = $_POST ['j'];
							// 	$resp ['type'] = "fb";
							// }
 						} 
                        else
                        {
							// if(Server_Side) {
								$resp = $api->getFBUserProfileServer ( $_POST['user_id'], $acc_data->accountId, $acc_data->sampleId );
							// }else{
							// 	$resp = $api->getFBUserProfile ( $acc_data->page_at, $_POST ['user_id']);
							// }
 						}
 						echo json_encode( $resp );
 					}
				}

                else if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ))
                {
                    $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );

                    if ($acc_data == 'reload') echo "Reload page";

                    else
                    {
                        if ( !isset( $_POST['user_id'] ) )
                        {
                            $resp = $api->getFBUserProfileServer( $acc_data->pageId, $acc_data->accountId, $acc_data->sampleId );
                            $resp->addChild('type', "fb");
                        }

                        else $resp = $api->getFBUserProfileServer( $_POST['user_id'], $acc_data->accountId, $acc_data->sampleId );

                        echo json_encode( $resp );
                    }
                }

                else echo "Incorrect data";
				break;
			case "getFbPostComments" :
				if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] ) && isset ( $_POST ["post_id"] )) {
					$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
					if ($acc_data == 'reload') {
						echo "Reload page";
					} else {
						$resp = $api->getFbPostComments ( $acc_data->user_at, $_POST ['post_id'] );
						echo json_encode ( $resp );
					}
				} else {
					echo "Incorrect data";
				}
				break;
			case "getFbEventsComments" :
				if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] ) && isset ( $_POST ["event_id"] )) {
					$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
					if ($acc_data == 'reload') {
						echo "Reload page";
					} else {
						$resp = $api->getFbPostsOfEvent ( $acc_data->page_at, $_POST ['event_id'] );
						echo json_encode ( $resp );
					}
				} else {
					echo "[]";
				}
				break;
			case "addEventsPost" :
				if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] ) && isset ( $_POST ["event_id"] ) && isset ( $_POST ["message"] )) {
					$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
					if ($acc_data == 'reload') {
						echo "Reload page";
					} else {
						if (Server_Side) {
							echo $api->publishSocialStreamObject ( "addEventsPost", $_POST ["event_id"], $_POST ["message"], "message" );
						} else {
							$resp = $api->addEventsPost ( $acc_data->user_at, $_POST ['event_id'], $_POST ['message'] );
							echo json_encode ( $resp );
						}
					}
				} else {
					echo null;
				}
				break;
			case "deleteFbEvent" :
				if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] ) && isset ( $_POST ['event_id'] )) {
					$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
					if ($acc_data == 'reload') {
						echo "Reload page";
					} else {
						if (Server_Side) {
							$resp = json_decode ( $api->publishSocialStreamObject ( "deleteEvent", $_POST ["event_id"], "", "message" ) );
							if ($resp->returnCode == "SUCCESS") {
								echo "true";
							} else {
								echo "false";
							}
						} else {
							$resp = $api->deleteEvent ( $acc_data->page_at, $_POST ['event_id'] );
							echo json_encode ( $resp );
						}
					}
				} else {
					echo "Incorrect data";
				}
				break;
			
            case "getVideos":
                // if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] ) && isset ( $_POST ['next'] )) {
				if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) && isset( $_POST['next'] ))
                {
                    // $acc_data = $api->getAccountData( $_POST ['i'], $_POST ['j'] );
					$acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );

					if ( $acc_data == 'reload') echo "Reload page";
                    else {
						// $resp = $api->getVideos($acc_data->page_at, $acc_data->pageId, $_POST['next_posts'], $_POST['next_videos']);
						// echo json_encode($resp);
						if ( Server_Side )
                        {
							echo $api->getNewsFeedMostRecentServer( $acc_data->accountId, $acc_data->sampleId, $_POST['stream'], $_POST['next'], "");
						}
                        else
                        {
							$token = $acc_data->user_at;
							$id = $acc_data->userId;
							echo $api->doFbRequest( $token, $id, $_POST['next'] );
						}
					}
				} 
                else echo "Incorrect data";

				break;

			case "getFBHidden_Groups":
				if ( isset( $_POST['accountID'] ) && isset( $_POST ['profileID'] ) && isset( $_POST['next_posts'] ))
                {
                    // $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
					$acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
					
                    if ($acc_data == 'reload') echo "Reload page";
                    else 
                    {
						if ( Server_Side )
                        {
							echo $api->getNewsFeedMostRecentServer( $acc_data->accountId, $acc_data->sampleId, $_POST['stream'], $_POST['next_posts'], "");
						}
                        else 
                        {
							$resp = $api->getFBHidden_Groups( $acc_data->user_at, $acc_data->userId, $_POST['next_posts'] );
							echo json_encode( $resp );
						}
					}
				} 
                else echo "Incorrect data";

				break;

			case 'getAccountsObj' :
				$accounts = new SimpleXMLElement ( $api->getAccountsData ( 'accounts' ) );
				$accounts = $accounts->analyticsAccounts->account;
				$s_accounts = Array ();
				foreach ( $accounts as $item ) {
					if (isset ( $accounts [$i]->userId ))
						$s_accounts [$i] = array (
								'type' => $item->type,
								'id' => $item->itemId
						);
				}
				echo json_encode ( $s_accounts );
				break;
			
            case 'getFbConversions':
                // if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] ) && isset ( $_POST ['next'] )) {
				if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) && isset( $_POST['next'] ) )
                {
                    // commented by Dan on May 1, 2014
					// if ( Server_Side ) 
                    // {
						$acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
						echo $api->getNewsFeedMostRecentServer( $acc_data->accountId, $acc_data->sampleId, $_POST['stream'], $_POST['next'], "");
					// } 
     //                else
     //                {
					// 	$i_val = intval ( $_POST ["i"] );
					// 	$j_val = intval ( $_POST ["j"] );
					// 	$accounts = new SimpleXMLElement ( $api->getAccountsData ( 'accounts' ) );
					// 	$accounts = $accounts->analyticsAccounts->account;
					// 	$page_at = $accounts [$i_val]->config [$j_val]->pageAccessToken;
					// 	$pageId = $accounts [$i_val]->config [$j_val]->pageId;
					// 	$resp = $api->getConversations ( $page_at, $pageId, $_POST ['next'] );
					// 	echo json_encode ( $resp );
					// }
				} 
                else echo "Incorrect data";

				break;

			case 'getFbLikes' :
				if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] ) && isset ( $_POST ['postId'] )) {
					$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
					if ($acc_data == 'reload') {
						echo "Reload page";
					} else {
						$resp = $api->getFbLikes ( $acc_data->user_at, $_POST ['postId'] );
						echo json_encode ( $resp );
					}
				} else {
					echo "Incorrect data";
				}
				break;
			
            case 'getFBGroup':
                // if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] ) && isset ( $_POST ['groupId'] )) {
				if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) && isset( $_POST['groupId'] ))
                {
					// $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
					
                    if ($acc_data == 'reload') echo "Reload page";

                    else 
                    {
						// if (Server_Side) {
                        // echo $api->getNewsFeedMostRecentServer( $acc_data->accountId, $acc_data->sampleId, $_POST['stream'], $_POST['next'], $_POST['groupId'] );
						echo $api->getNewsFeedMostRecentServer( $_POST['accountID'], $_POST['profileID'], $_POST['stream'], $_POST['next'], $_POST['groupId'] );
						// } else {
						// 	$resp = $api->getFBGroup ( $acc_data->user_at, $_POST ['groupId'], $_POST ['next'] );
						// 	echo json_encode ( $resp );
						// }
					}
				} 
                else echo "Incorrect data";

				break;

            case 'getFBLink':
                if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) && isset( $_POST['postId'] ) ) 
                {
                    $post = $api->getNewsFeedMostRecentServer( $_POST['accountID'], $_POST['profileID'], "getFBLink", $_POST['next'], $_POST['postId'] );
                    
                    echo $post;
                } 

                else echo "Incorrect data";
                break;

            case 'getFBPhoto':
                if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) && isset( $_POST['postId'] ) ) 
                {
                    $post = $api->getNewsFeedMostRecentServer( $_POST['accountID'], $_POST['profileID'], "getFBPhoto", $_POST['next'], $_POST['postId'] );
                    
                    echo $post;
                } 

                else echo "Incorrect data";
                break;

            case 'getFBVideo':
                if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) && isset( $_POST['postId'] ) ) 
                {
                    $post = $api->getNewsFeedMostRecentServer( $_POST['accountID'], $_POST['profileID'], "getFBVideo", $_POST['next'], $_POST['postId'] );
                    
                    echo $post;
                } 

                else echo "Incorrect data";
                break;

			case 'getSingleFBPost':

				if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] ) && isset ( $_POST ['postId'] )) {
					$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
					if ($acc_data == 'reload') {
						echo "Reload page";
					} else {
						echo $api->getNewsFeedMostRecentServer ( $acc_data->accountId, $acc_data->sampleId, "getSingleFBPost", $_POST ['next'], $_POST ['postId'] );
					}
                } 

                else if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) && isset( $_POST['postId'] ) ) 
                {
                    // $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
                    // if ($acc_data == 'reload') echo "Reload page";
                    // else
                    // {
                    $post = $api->getNewsFeedMostRecentServer( $_POST['accountID'], $_POST['profileID'], "getSingleFBPost", $_POST['next'], $_POST['postId'] );
                    
                    echo $post;
                    // }
				} 

                else echo "Incorrect data";

				break;

			case 'addToDashboard' :
				if ( isset( $_POST ['data'] ) ) {
                    
    				/*$accounts = new SimpleXMLElement ( $api->getAccountsData ( 'accounts' ) );
    				$accounts = $accounts->analyticsAccounts->account;
    				$i = intval ( $_POST ['data'] ['id'] ['i'] );
    				$j = intval ( $_POST ['data'] ['id'] ['j'] );
    				$data ['accountId'] = $accounts [$i]->accountId;
    				$data ['sampleId'] = $accounts [$i]->config [$j]->sampleId;*/

                    $data ['accountId'] = $_POST ['data'] ['accountID'];
                    $data ['sampleId'] = $_POST ['data'] ['profileID'];
    				$data ['summaryLevel'] = $_POST ['data'] ['summaryLevel'];
    				//$data ['displayName'] = $_POST ['data'] ['displayName'];
                    $data ['graphId'] = $_POST ['data'] ['graphId'];
                    
    				echo $api->addDashboardMetric ( $data );
				}
				break;
			case 'getDashboardData' :
                // $archive = $api->getDashboardSummaryData ();
                // $archive = base64_decode ( $archive );
                // $archive = substr ( $archive, 4 );
                // file_put_contents ( 'archive/' . $_SESSION ['user_name'] . '_dashboard.gz', $archive );
                // $gz = gzopen ( 'archive/' . $_SESSION ['user_name'] . '_dashboard.gz', "r" );
                // $response = "";
                // while ( ! gzeof ( $gz ) ) {
                //     $response .= gzgets ( $gz, 255 );
                // }
                // gzclose ( $gz );
                // $response = urldecode ( $response );
                // $xml = simplexml_load_string ( $response );
                // // saving dashboard data
                // $api->saveAccountsData ( $xml->asXML (), 'dashboard_data' );
				$xml = new SimpleXMLElement ( $api->getAccountsData ( 'dashboard_data' ) );
                if ($log_response_to_ec_error_handler)
                    ECErrorHandler::error('xml exporting - '.print_r($xml, true));
				$accounts = $xml->analyticsAccounts->account;
				$json = Array ();
				for($i = 0; $i < count ( $accounts ); $i ++) {
                    // ECErrorHandler::error('i is '. $i);
					if ($accounts [$i]->config->monitored == 'on') {
                        // ECErrorHandler::error("$i is on");
						$type = $accounts [$i]->type;
						$graphs = null;
						$mail = "";
						switch ($type) :
							case 'Facebook' :
								$graphs = $accounts [$i]->config->FacebookAnalyticsMetrics;
								$mail = $accounts [$i]->config->pageName;
								break;
							case 'WordPress' :
								$graphs = $accounts [$i]->config->WordPressAnalyticsMetrics;
								$mail = $accounts [$i]->config->blogName;
								break;
							case 'GoogleAnalytics' :
								$graphs = $accounts [$i]->config->GoogleAnalyticsMetrics;
								$mail = $accounts [$i]->config->title;
								break;
							case 'Twitter' :
								$graphs = $accounts [$i]->config->TwitterAnalyticsMetrics;
								$mail = $accounts [$i]->config->specifiedHandleOrHashTag;
								break;
							case 'YouTube' :
								$graphs = $accounts [$i]->config->YouTubeAnalyticsMetrics;
								$mail = $accounts [$i]->config->userFirstName;
								break;
							case 'Linkedin' :
								$graphs = $accounts [$i]->config->LinkedInAnalyticsMetrics;
								$mail = $accounts [$i]->config->profileName;
								break;
                            default:
                                ECErrorHandler::error("Unknown type: $type");
                                break;
						endswitch
						;
						// echo "__".urldecode($accounts[$i]->nickName)."____";
                        // ECErrorHandler::error('graphs data here ' . print_r($graphs, true) );
						if (isset ( $graphs->sample )) {
                            foreach ($graphs->sample as $tempa) {
                                // ECErrorHandler::error('tempa data here ' . print_r($tempa, true) );
                                if (!isset($tempa->isMobile) || $tempa->isMobile == 'false') {
                                    $tempa = (array)$tempa;
                                    $json [] = Array (
                                            'displayName' => ifNotArrayReturnArray($tempa['displayName']),
                                            'graphId' => ifNotArrayReturnArray($tempa['graphId']),
                                            'category' => ifNotArrayReturnArray($tempa['category']),
                                            'summaryLevel' => ifNotArrayReturnArray($tempa['summaryLevel']),
                                            'xText' => ifNotArrayReturnArray($tempa['xText']),
                                            'yText' => ifNotArrayReturnArray($tempa['yText']),
                                            'status' => ifNotArrayReturnArray($tempa['status']),
                                            'r' => ifNotArrayReturnArray($tempa['r']),
                                            'nickName' => urldecode ( $accounts [$i]->nickName ),
                                            'id_val' => $i,
                                            'type' => $accounts [$i]->type,
                                            'mail' => $mail,
                                            'display' => ( string ) $tempa['display'],
                                            't' => ifNotArrayReturnArray($tempa['t']),
                                            'graphMessage' => ifNotArrayReturnArray($tempa['graphMessage']),
                                            'handler' => ifNotArrayReturnArray($tempa['handler'])
                                        );
                                    
                                    break;
                                }
                            }
						}
					}
				}
                $encodedjson = json_encode ( $json );
                if ($log_response_to_ec_error_handler)
                    ECErrorHandler::error('jsexporting -  ' . print_r($encodedjson, true) );
				echo $encodedjson;
				break;
			case 'removeDashboardGraph' :
				if (isset ( $_POST ['data'] )) {
					$xml = new SimpleXMLElement ( $api->getAccountsData ( 'dashboard_data' ) );
					$accounts = $xml->analyticsAccounts->account;
					$data = new stdClass ();
					$i = intval ( $_POST ['data'] ['id'] );
					$type = $accounts [$i]->type;
					$data->summaryLevel = $_POST ['data'] ['summaryLevel'];
					//$data->displayName = $_POST ['data'] ['displayName'];
                    $data->graphId = $_POST ['data'] ['graphId'];
					$data->accountId = $accounts [$i]->accountId;
					$data->sampleId = $accounts [$i]->config->sampleId;
					echo $api->removeDashboardMetric ( $data );
				}
				break;
			case 'getPieChartData' :
				$accounts = new SimpleXMLElement ( $api->getAccountsData ( 'accounts' ) );
				$accounts = $accounts->analyticsAccounts->account;
				$i = intval ( $_POST ['val1'] );
				$j = intval ( $_POST ['val2'] );
				$sampleId = $accounts [$i]->config [$j]->sampleId;
				$obj = $api->getPieChartSampleIdData ( $sampleId );
				if ($obj->returnCode == 'SUCCESS') {
					$sample = $obj->PieChartMetrics->sample;
					for($i = 0; $i < count ( $sample ); $i ++) {
						$json [] = $sample [$i];
					}
					echo json_encode ( $json );
				} else {
					echo $obj->returnCode;
				}
				break;
			case 'setDashboardGraph' :
				if (isset ( $_POST ['data'] )) {
                    $api->clearSettingsCache();
					$xml = new SimpleXMLElement ( $api->getAccountsData ( 'dashboard_data' ) );
					$accounts = $xml->analyticsAccounts->account;
					$data = new stdClass ();
					$i = intval ( $_POST ['data'] ['id'] );
					$data->accountId = $accounts [$i]->accountId;
					$data->sampleId = $accounts [$i]->config->sampleId;
					$data->aldLevel = $_POST ['data'] ['oldLevel'];
					$data->summaryLevel = $_POST ['data'] ['summaryLevel'];
					$data->displayName = $_POST ['data'] ['displayName'];
                    $data->graphId = $_POST ['data'] ['graphId'];
					$data->newPosition = $_POST ['data'] ['position'];
					$response = $api->setDashboardMetric ( $data );
					if ($response->returnCode == "SUCCESS") {
						$response = $response->analyticsAccounts->account;
						$graphs = null;
						switch ($response->type) :
							case 'Facebook' :
								$graphs = $response->config->FacebookAnalyticsMetrics->sample;
								break;
							case 'WordPress' :
								$graphs = $response->config->WordPressAnalyticsMetrics->sample;
								break;
							case 'GoogleAnalytics' :
								$graphs = $response->config->GoogleAnalyticsMetrics->sample;
								break;
							case 'Twitter' :
								$graphs = $response->config->TwitterAnalyticsMetrics->sample;
								break;
							case 'YouTube' :
								$graphs = $response->config->YouTubeAnalyticsMetrics->sample;
								break;
							case 'Linkedin' :
								$graphs = $response->config->LinkedInAnalyticsMetrics->sample;
								break;
						endswitch
						;
						$graphMessage = null;
						if (isset ( $graphs->sample->graphMessage )) {
							$graphMessage = $graphs->sample->graphMessage;
						}
                        
                        // ECErrorHandler::error('debugging here farley '. print_r($graphs, true));
                        
                        $tempa = (array)$graphs;
                        $json [] = Array (
                                'displayName' => ifNotArrayReturnArray($tempa['displayName']),
                                'graphId' => ifNotArrayReturnArray($tempa['graphId']),
                                'category' => ifNotArrayReturnArray($tempa['category']),
                                'summaryLevel' => ifNotArrayReturnArray($tempa['summaryLevel']),
                                'xText' => ifNotArrayReturnArray($tempa['xText']),
                                'yText' => ifNotArrayReturnArray($tempa['yText']),
                                'status' => ifNotArrayReturnArray($tempa['status']),
                                'r' => ifNotArrayReturnArray($tempa['r']),
                                'nickName' => urldecode ( $accounts [$i]->nickName ),
                                'id_val' => $i,
                                'type' => $accounts [$i]->type,
                                'mail' => $mail,
                                'display' => ( string ) $tempa['display'],
                                't' => ifNotArrayReturnArray($tempa['t']),
                                'graphMessage' => ifNotArrayReturnArray($graphMessage),
                                'handler' => ifNotArrayReturnArray($graphs->handler)
                            );

						echo json_encode ( $json );
					} else {
						echo $response->returnCode;
						var_dump ( $response );
					}
				}
				break;
			case 'test' :
				echo json_encode ( $_POST ['data'] );
				break;

			case 'getSampleData':
				if ( !$api->ifUserLogined() ) echo 'reload';

                // elseif ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) && isset( $_POST['accountId'] ) )
                elseif ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) )
                {
                    $account = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );

                    // if ( isset( $account->accountId ) && isset( $account->sampleId ) && ( $_POST['accountId'] == md5( $account->accountId ) ) )
                    if ( isset( $account->accountId ) && isset( $account->sampleId ) )
                    {
						$has_cook = false;
						$hash_code = md5 ( $account->accountId . $account->sampleId );
						$time = null;
						
                        if ( isset ( $_POST['list'] ) )
                        {
							for( $num = 0; $num < count ( $_POST['list'] ); $num ++ )
                            {
								if ( $_POST['list'][ $num ] == $hash_code )
                                {
									$has_cook = true;
									$time = $_POST['time'][ $num ];
									break;
								}
							}
						}
						
                        $json = Array ();
						
                        if ( $has_cook && $account->type == "Facebook" && isset ( $_POST['time'] ) )
                        {
							$resp = $api->dataIsReadyCheck( $account->accountId, $account->sampleId );
							
                            if ( $resp->sampleAnalyticsTime != $time )
                            {
								$has_cook = false;
								$time = $resp->sampleAnalyticsTime;
							}

							$json["load_time"] = $time;
						}

						if ( !$has_cook )
                        {
							$el = $api->getAnalyticsSampleIdData( $account->accountId, $account->sampleId );
							$compressed = base64_decode( $el->archive );
							$compressed = substr( $compressed, 4 );
							file_put_contents( sys_get_temp_dir() . "/archive_" . $_SESSION['user_name'] . "_analytics.gz", $compressed );
							$gz = gzopen( sys_get_temp_dir() . "/archive_" . $_SESSION['user_name'] . "_analytics.gz", "r" );
							$response = "";
							
                            while ( !gzeof( $gz ) ) $response .= gzgets( $gz, 255 );

							gzclose( $gz );
							
                            $response = urldecode( $response );

							$xml = simplexml_load_string( $response, 'SimpleXMLElement', LIBXML_COMPACT | LIBXML_PARSEHUGE );
							$graph_accounts = $xml->analyticsAccounts->account;
							$type = $graph_accounts->type;
							
                            switch ( $type ):
								case 'Facebook':
									$graphs = $xml->analyticsAccounts->account->config->FacebookAnalyticsMetrics->sample;
									break;
								case 'WordPress':
									$graphs = $xml->analyticsAccounts->account->config->WordPressAnalyticsMetrics->sample;
									break;
								case 'GoogleAnalytics':
									$graphs = $xml->analyticsAccounts->account->config->GoogleAnalyticsMetrics->sample;
									break;
								case 'Twitter':
									$graphs = $xml->analyticsAccounts->account->config->TwitterAnalyticsMetrics->sample;
									break;
								case 'YouTube':
									$graphs = $xml->analyticsAccounts->account->config->YouTubeAnalyticsMetrics->sample;
									break;
                                case 'Linkedin':
                                    $graphs = $xml->analyticsAccounts->account->config->LinkedInAnalyticsMetrics->sample;
                                    break;
								default:
									return null;
									break;
							endswitch;

							foreach ( $graphs as $item )
                            {
								$item_data = array (
										'status' => $item->status,
										'wait_message' => $xml->analyticsAccounts->waitMessage,
										'startDate' => $item->startDate,
										'endDate' => $item->endDate,
										'display' => (string) $item->display
								);

                                $item_data['r'] = new stdClass();
                                
                                if ( is_string( $item->r ) ) $item_data['r']->{'0'} = $item->r;

                                else
                                {
                                    $y = 0;
                                    foreach ($item->r as $key => $value)
                                    {
                                        $item_data['r']->$y = (string) $value;
                                        $y++;
                                    }
                                }

                                if ( is_string( $item->displayName ) ) $item_data['displayName'] = (object) array('0' => $item->displayName );
                                else $item_data['displayName'] = $item->displayName;

                                if ( is_string( $item->graphId ) ) $item_data['graphId'] = (object) array('0' => $item->graphId );
                                else $item_data['graphId'] = $item->graphId;
                                
                                if ( is_string( $item->xText ) ) $item_data['xText'] = (object) array('0' => $item->xText );
                                else $item_data['xText'] = $item->xText;
                                
                                if ( is_string( $item->yText ) ) $item_data['yText'] = (object) array('0' => $item->yText );
                                else $item_data['yText'] = $item->yText;
                                
                                if ( isset( $item->t ) )
                                {
                                    if ( is_string( $item->t ) ) $item_data['t'] = (object) array('0' => $item->t );
                                    else $item_data['t'] = $item->t;
                                }
                                else $item_data['t'] = new stdClass();

                                if ( SYSTEM_TYPE == 'QA' && isset( $item->actualSampleTime ) )
                                {
                                    if ( is_string( $item->actualSampleTime ) ) $item_data['actualSampleTime'] = (object) array('0' => $item->actualSampleTime );
                                    else $item_data['actualSampleTime'] = $item->actualSampleTime;
                                }

                                if ( isset( $item->graphMessage ) )
                                {
                                    if ( is_string( $item->graphMessage ) ) $item_data['graphMessage'] = (object) array('0' => $item->graphMessage );
                                    else $item_data['graphMessage'] = $item->graphMessage;
                                }
                                else $item_data['graphMessage'] = new stdClass();

                                if ( isset( $item->handler ) )
                                {
                                    if ( is_string( $item->handler ) ) $item_data['handler'] = (object) array('0' => $item->handler );
                                    else $item_data['handler'] = $item->handler;
                                }
                                else $item_data['handler'] = new stdClass();
								
                                if ( $type == "GoogleAnalytics") $item_data['mobile'] = ( string ) $item->isMobile;

                                $category = ( string ) $item->category;
                                
                                //tricks for 'Sources' tab
                                if ( $type == "GoogleAnalytics")
                                {
                                    if ( $category == "Social") $item_data['social'] = true;
                                    else if( $category == "Sources") $item_data['social'] = false;
                                }    
								
                                if ( $category == "") $category = "Twitter";

								$json[ $category ][ (string) $item->summaryLevel ][] = $item_data;
							}
							
                            system( "rm -r " . sys_get_temp_dir() . "/archive_" . $_SESSION ['user_name'] . "_analytics.gz" );

							if ( $account->type == "Facebook")
                            {
								if ( $time == null )
                                {
									$resp = $api->dataIsReadyCheck( $account->accountId, $account->sampleId );
									$json["load_time"] = $resp->sampleAnalyticsTime;
								} 
                                else $json["load_time"] = $time;
							} 
                            else $json["load_time"] = 0;
						}

						$json["hash_tag"] = $hash_code;
						$json["has_cook"] = $has_cook;
                        $json = json_encode( $json );
						echo $json;
                    }
                    
                    else echo 'reload';
				}
				break;
			case 'updateAccounts' :
				if (isset ( $_POST ['data'] )) {
					$settings = $api->getSettings ()->settings;
					$settings->firstName = $_POST ['data'] ['firstname'];
					$settings->lastName = $_POST ['data'] ['lastname'];
					$settings->company = $_POST ['data'] ['company'];
					$settings->city = $_POST ['data'] ['city'];
					$settings->state = $_POST ['data'] ['state'];
					$settings->country = $_POST ['data'] ['country'];
					if (isset ( $_POST ['data'] ['new_password'] ))
						$settings->newPassword = $_POST ['data'] ['new_password'];
					$response = $api->setSettings ( $settings );
					if ($response == "SUCCESS" && isset ( $_POST ['data'] ['new_password'] )) {
						$_SESSION ['user_pass'] = $_POST ['data'] ['new_password'];
					}
					echo $response;
				}
				break;
			case 'changePassword' :
				if (isset ( $_POST ['password'] ) && isset ( $_POST ['forgot_id'] )) {
                    MultiSession::logoutUser();
					echo $api->changePassword ();
				}
				break;
			case 'sendChartToMail':
                if (isset ( $_POST ['mails'] ) && trim ( $_POST ['mails'] ) != '' && isset ( $_POST ['elem'] ) && isset ( $_POST ['note'] ) && isset ( $_POST ['svg_w'] ) && isset ( $_POST ['svg_h'] )) {
                    $message = $_POST ['elem'];
                    $note = $_POST ['note'];
                    $width = intval ( $_POST ['svg_w'] );
                    $height = intval ( $_POST ['svg_h'] );
                    try {
                        if ($_POST ['send_all'] == "true") {
                            $message = $api->svgToPDF ( $message, $width, $height );
                            $type = "pdf";
                        } else {
                            $message = $api->svgToPng ( $message, $width, $height );
                            $type = "png";
                        }
                    } catch (Exception $e) {
                        echo "Internal error while attempting to generate graph image";
                    }
                    if (strpos ( $_POST ['mails'], "," )) {
                        $mails = explode ( ",", $_POST ['mails'] );
                        $incorrectMails = 0;
                        foreach ( $mails as $mail ) {
                            if (! filter_var ( trim ( $mail ), FILTER_VALIDATE_EMAIL )) {
                                $incorrectMails ++;
                            }
                        }
                        if ($incorrectMails >= 1) {
                            echo "Your email was not sent. Please check the email address and try again. Use a comma to separate multiple email addresses.";
                        } else {
                            echo $api->sendMailTest ( $message, $mails, $note, $type );
                        }
                    } elseif (filter_var ( trim ( $_POST ['mails'] ), FILTER_VALIDATE_EMAIL )) {
                        echo $api->sendMailTest ( $message, $_POST['mails'], $note, $type );
                    } else {
                        echo "Your email was not sent. Please check the email address and try again. Use a comma to separate multiple email addresses.";
                    }
                } else {
                    echo "Incorrect data!";
                }
				break;

            case "getAccountsData":
                    // echo json_encode( $api->getAccontsData() ); //<-- Note the misspelling of "Account"
                    if ( $api->logged_in() ) echo json_encode( $api->getAccontsData() ); //<-- Note the misspelling of "Account"

                    else echo '[]';
                break;
            
            case "getAnalyticsAccounts":
                echo json_encode( simplexml_load_string( $api->getAccountsData('accounts') ) );
                break;
                        
            // case "addSavedUrls":
            case "savedUrls":

                $payload = array_to_xml( $_POST['data'] );

                $request = simplexml_load_string('<request>'.$payload.'</request>'); 

                $saved = $api->addSavedUrls( $request );

                echo json_encode( $saved );

                break;

			case "deleteSavedUrls":

                // $url = $api->addSavedUrls( $_POST['url'] );
                $url = $_POST['url'];

                echo json_encode('{"url":"'.$url.'"}');

				break;

			case "schedulePost":
				$resp = new stdClass ();
				if (isset ( $_POST ['accounts'] ) && isset ( $_POST ['message'] )) {
					if (trim ( $_POST ['message'] ) != "" || isset ( $_POST ["attachment"] )) {
						$attachment = isset ( $_POST ["attachment"] ) ? $_POST ['attachment'] : null;
						$save = ($_POST ['save'] == "false") ? "addScheduledPost" : "setScheduledPost"; //commented feb/13/14
						// $save = "addScheduledPost";
						$postId = isset ( $_POST ['postId'] ) ? $_POST ['postId'] : null;
						$link = isset ( $_POST ['link'] ) ? $_POST ['link'] : null;
						$response = $api->addScheduledPost ( $_POST ['accounts'], $_POST ['message'], $attachment, $_POST ['post_time'], $_POST ['email_me'], $save, $postId, $_POST ["post_now"], $link );
						if ($response->returnCode != "SUCCESS") {
							$resp->code = ( string ) $response->returnCode;
							if (isset ( $response->description )) {
								$resp->description = ( string ) $response->description;
							} else {
								$resp->description = "Error: Your post did not go through.<br>Please contact customer support.";
							}
						} else {
							$resp->code = ( string ) $response->returnCode;
							$resp->description = ( string ) $response->description;
						}
					} else {
						$resp->description = "Message field is empty!";
					}
				} else {
					$resp->description = "Please select account(s)";
				}
				echo json_encode ( $resp );
				break;

            case "getAccounts":
                
                if ( ! $api->ifUserLogined() ) echo 'reload';

                else {
                    $accounts = new SimpleXMLElement( $api->getAccountsData('accounts', true ) ); // second param: bool <= skip_cache

                    echo json_encode( $accounts );
                }
                break;

			case "getPosts":
				
                if (! $api->ifUserLogined ()) echo 'reload';

                else 
                {
					$resp = $api->getScheduledPosts();

					for( $i = 0; $i < count( $resp->posts->post ); $i++ )
                    {
						for( $ai = 0; $ai < count( $resp->posts->post[ $i ]->accounts->account ); $ai++ )
                        {
							$resp->posts->post[ $i ]->accounts->account[ $ai ]->accountId = $api->getI( $resp->posts->post[ $i ]->accounts->account[ $ai ]->accountId );
							for($aj = 0; $aj < count ( $resp->posts->post [$i]->accounts->account [$ai]->config->sampleId ); $aj ++) {
								$resp->posts->post [$i]->accounts->account [$ai]->config->sampleId [$aj] = $api->getJ ( intval ( $resp->posts->post [$i]->accounts->account [$ai]->accountId ), $resp->posts->post [$i]->accounts->account [$ai]->config->sampleId [$aj] );
							}
						}
					}
					echo json_encode ( $resp->posts );
				}
				break;

			case "deletePost" :
				if (isset ( $_POST ['id'] )) {
					$resp = $api->deleteScheduledPost ( $_POST ['id'] );
					echo $resp->description;
				} else {
					echo "Post not defined";
				}
				break;
			default :
				break;
			case "getStreams" :
				if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] )) {
					$xml = new SimpleXMLElement ( $api->getAccountsData ( 'accounts' ) );
					$accounts = $xml->analyticsAccounts->account;
					$i_val = intval ( $_POST ['i'] );
					$j_val = intval ( $_POST ['j'] );
					$accountId = $accounts [$i_val]->accountId;
					$sampleId = $accounts [$i_val]->config [$j_val]->sampleId;
					$result = $api->getAccountStreams ( $accountId, $sampleId );
					echo json_encode ( $result->accountStreams );
				} else {
					echo "data error";
				}
				break;

			case "setStreams": 
                // if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] ) && isset ( $_POST ['streams'] )) {
				if ( isset( $_POST['accountID'] ) && isset( $_POST ['profileID'] ) && isset( $_POST['streams'] ))
                {
					// $xml = new SimpleXMLElement ( $api->getAccountsData( 'accounts' ) );
					// $accounts = $xml->analyticsAccounts->account;
					// $i_val = intval ( $_POST ['i'] );
					// $j_val = intval ( $_POST ['j'] );
					// $accountId = $accounts [$i_val]->accountId;
					// $sampleId = $accounts [$i_val]->config [$j_val]->sampleId;
					// $type = $accounts [$i_val]->type;
                    // $result = $api->setAccountStreams( $accountId, $sampleId, $type );
					$result = $api->setAccountStreams( $_POST['accountID'], $_POST ['profileID'] );
					
                    echo $result->returnCode;
				} 

                else echo "data error";

				break;

			case "sendPostToMail":
				if (isset ( $_POST ['to'] ) && trim ( $_POST ['to'] ) != '' && isset ( $_POST ['name'] ) && isset ( $_POST ['date'] ) && isset ( $_POST ['src'] )) {
					if (strpos ( $_POST ['to'], "," )) {

						// 2 & more emails
						$mails = explode ( ",", $_POST ['to'] );

						$incorrectMails = 0;

						foreach ( $mails as $mail ) {

							if (! filter_var ( trim ( $mail ), FILTER_VALIDATE_EMAIL )) {
								$incorrectMails ++;
							}
						}

						if ($incorrectMails >= 1) {
							//echo "Your email were not sent. Please add comma between emails.";
							echo "Your email was not sent. Please check the email address and try again. Use a comma to separate multiple email addresses.";
						} else {
							echo $api->sendPostToMail ( $_POST ['to'], htmlspecialchars ( $_POST ['note'] ), $_POST ['name'], $_POST ['date'], $_POST ['message'], $_POST ['src'], $_POST ['photoVideo'] );
						}
					} elseif (filter_var ( trim ( $_POST ['to'] ), FILTER_VALIDATE_EMAIL )) {
						// just 1 mail :
						echo $api->sendPostToMail ( $_POST ['to'], htmlspecialchars ( $_POST ['note'] ), $_POST ['name'], $_POST ['date'], $_POST ['message'], $_POST ['src'], $_POST ['photoVideo'] );
					} else {
						//echo "Your email were not sent. Please add comma between emails.";
						echo "Your email was not sent. Please check the email address and try again. Use a comma to separate multiple email addresses.";
					}
				} else {
					echo "Incorrect data!";
				}
				break;
			case "sendMessageToMail" :
				if (isset ( $_POST ['to'] ) && ! (trim ( $_POST ['to'] ) == '' || ! filter_var ( $_POST ['to'], FILTER_VALIDATE_EMAIL )) && isset ( $_POST ["message"] ) && (trim ( $_POST ['message'] ) != '') && isset ( $_POST ['from'] ) && ! (trim ( $_POST ['from'] ) == '' || ! filter_var ( $_POST ['from'], FILTER_VALIDATE_EMAIL ))) {
					echo $api->sendMessageToMail ( $_POST ['from'], $_POST ['to'], $_POST ['message'] );
				} else {
					echo "Incorrect data!";
				}
				break;
			case "sign_up" :
				$error = null;
				$resp = null;
				if (trim ( $_POST ['email'] ) == '' || ! filter_var ( $_POST ['email'], FILTER_VALIDATE_EMAIL )) {
					$error = "invalid_mail";

					/*
					 * Removed validation below per Tals request j.c. 6-19-13 } elseif(trim($_POST['firstname']) == ''){ $error="invalid_firstname"; } elseif(trim($_POST['lastname'])==""){ $error="invalid_lastname";
					 */
				} elseif (strlen ( trim ( $_POST ['password'] ) ) < 4) {
					$error = "short_password";
				} else {
					$plan = $_POST ['plan'] /*'Basic'*/;
					$paymentId = null;
					$paymentLicense = $plan;
					$accountCode = null;
					$paymentState = null;
					$createdAt = 0;
					if (trim ( $plan ) == 'Basic' || trim ( $plan ) == 'Premier') {
						try {

							$subscription = new Recurly_Subscription ();
							$subscription->plan_code = $plan;
							$subscription->currency = "USD";
							$recAccount = new Recurly_Account ();
							$recAccount->account_code = $_POST ['email'];
							$recAccount->email = $_POST ['email'];
							$recAccount->first_name = $_POST ['credit_first_name'];
							$recAccount->last_name = $_POST ['credit_last_name'];
							$billing_info = new Recurly_BillingInfo ();
							$billing_info->address1 = $_POST ['street_address'];
							$billing_info->country = $_POST ['country'];
							$billing_info->zip = $_POST ['zip'];
							$billing_info->city = $_POST ['city'];
							if ($_POST ['state'] != null && strlen ( $_POST ['state'] ) > 0) {
								$billing_info->state = $_POST ['state'];
							}
							$billing_info->number = $_POST ['credit_num'];
							$billing_info->verification_value = $_POST ['credit_code'];
							$billing_info->month = $_POST ['credit_month'];
							$billing_info->year = $_POST ['credit_year'];
							$recAccount->billing_info = $billing_info;
							$subscription->account = $recAccount;
							$rec_response = $subscription->create ();
							$xmlResp = new SimpleXMLElement ( trim ( $rec_response->GetBody () ) );
							$paymentId = $xmlResp->uuid;
							$accountCode = $_POST ['email'];
							$paymentState = $xmlResp->state;
							$createdAt = strtotime ( $xmlResp->activated_at ) . "000";
						} catch ( Recurly_NotFoundError $e ) {
							echo 'Error: Record could not be found';
							break;
						} catch ( Recurly_ValidationError $e ) {
							$messages = explode ( ',', $e->getMessage () );
							echo 'Error: Validation problems: ' . implode ( "\n", $messages );
							break;
						} catch ( Recurly_ServerError $e ) {
							echo 'Error: Problem communicating with Recurly';
							break;
						} catch ( Exception $e ) {
							echo "Error: " . $e->getMessage ();
							echo "Error: " . $e;
							break;
						}
					}

					$resp = $api->sign_up ( $_POST ['email'], $_POST ['password'], '', '', '', '', '', '', $paymentId, $paymentState, $paymentLicense, $accountCode, $createdAt );
					// $resp =$api->sign_up($_POST['email'], $_POST['password'], $_POST['firstname'], $_POST['lastname'], $_POST['company'], $_POST['city'], $_POST['state'], $_POST['country']);
				}
				$json = new stdClass ();
				if ($resp != null) {
					if (isset ( $resp->returnCode )) {
						$json->code = ( string ) $resp->returnCode;
						$respDecr = ( string ) $resp->description;
						if (strpos ( $respDecr, ';;;' ) !== False) {
							list($textDesc, $urlToDecode) = explode ( ";;;", $respDecr );
							$urlToDecode = base64_decode ( $urlToDecode );
							$respDecr = $textDesc . ";;;" . $urlToDecode;
						}
						$json->description = $respDecr;
					} else {
						$json->code = "FAIL";
						$json->error = $resp;
					}
				} else {
					$json->code = "FAIL";
					$json->error = $error;
				}
				echo json_encode ( $json );
				break;
			case "changePlan" :
				// echo "Start";
				if (isset ( $_POST ['email'] )) {
					$email = $_POST ['email'];
				} else {
					$email = $_SESSION ['user_name'];
				}
				if (isset ( $_POST ['email'] )) {
					$pass = $_POST ['password'];
					$r = $api->verifyCredentials ( $_POST ['email'], $pass );
				} else {
					$pass = $_SESSION ['user_pass'];
					$r = new stdClass ();
					$r->returnCode = "SUCCESS";
				}

				if ($r->returnCode == "SUCCESS") {
					$alertMsg = 'You successfully signed up for eClincher ';
					$paymentId = null;
					$paymentLicense = $_POST ['plan'];
					$paymentConfig = $api->getUserPaymentConfig ( $email, SERVER_IDENTIFIER );
					$previousPlan = null;
					$paymentState = null;
					if ($paymentConfig != null && $paymentConfig != '') {
						if ($paymentConfig->paymentLicense) {
							$previousPlan = $paymentConfig->paymentLicense;
						}
					}
					if ($previousPlan != null && $previousPlan != '') {
						if ($previousPlan == $paymentLicense) {
							break;
						}
						$paymentId = $paymentConfig->paymentId;
						$paymentState = $paymentConfig->paymentState;
					} else {
						if ($paymentLicense == 'Free') {
							break;
						}
					}
					if ($paymentLicense != 'Free') {
						if ($paymentId != null && strlen ( $paymentId ) > 0 && $paymentState != null && $paymentState != 'expired') {
							$subscription = Recurly_Subscription::get ( $paymentId );
							if ($subscription->state == 'canceled') {
								$subscription->reactivate ();
							}
							$subscription->plan_code = $paymentLicense;
							try {
								$rec_response = $subscription->updateImmediately ();
								$xmlResp = new SimpleXMLElement ( trim ( $rec_response->GetBody () ) );
								$paymentState = $xmlResp->state;
								if ($paymentState == 'active') {
									$api->updateUserPaymentConfig ( SERVER_IDENTIFIER, $paymentLicense, $email, null );
									$api->setUserLicense ( $email, SERVER_IDENTIFIER );
									echo $alertMsg . $paymentLicense . '.';
								}
							} catch ( Exception $e ) {
								echo $e->getMessage ();
								break;
							}
						} else {
							if (! array_key_exists ( 'credit_num', $_POST )) {
								$json = new stdClass ();
								$json->userName = $email;
								$json->password = $pass;
								echo "SHOW_POP_UP-" . json_encode ( $json );
								break;
							}
							try {
								$subscriptions = Recurly_SubscriptionList::getForAccount ( $email );
								foreach ( $subscriptions as $sub ) {
									if ($sub->state == 'canceled') {
										$subscription = Recurly_Subscription::get ( $sub->uuid );
										$subscription->terminateWithoutRefund ();
									}
								}
							} catch ( Exception $e ) {
							}
							try {
								$subscription = new Recurly_Subscription ();
								$subscription->plan_code = $paymentLicense;
								$subscription->currency = "USD";
								$recAccount = new Recurly_Account ();
								$recAccount->account_code = $email;
								$recAccount->email = $email;
								$recAccount->first_name = $_POST ['credit_first_name'];
								$recAccount->last_name = $_POST ['credit_last_name'];
								$billing_info = new Recurly_BillingInfo ();
								$billing_info->address1 = $_POST ['street_address'];
								$billing_info->country = $_POST ['country'];
								$billing_info->zip = $_POST ['zip'];
								$billing_info->city = $_POST ['city'];
								if ($_POST ['state'] != null && strlen ( $_POST ['state'] ) > 0) {
									$billing_info->state = $_POST ['state'];
								}
								$billing_info->number = $_POST ['credit_num'];
								$billing_info->verification_value = $_POST ['credit_code'];
								$billing_info->month = $_POST ['credit_month'];
								$billing_info->year = $_POST ['credit_year'];
								$recAccount->billing_info = $billing_info;
								$subscription->account = $recAccount;
								$rec_response = $subscription->create ();
								$xmlResp = new SimpleXMLElement ( trim ( $rec_response->GetBody () ) );
								$paymentId = $xmlResp->uuid;
								$accountCode = $email;
								$paymentState = $xmlResp->state;
								$createdAt = strtotime ( $xmlResp->activated_at ) . "000";
								if ($paymentState == 'active') {
									$api->setPaymentConfig ( $email, $pass, SERVER_IDENTIFIER, $paymentId, $accountCode, $paymentLicense, $paymentState, $createdAt );
									$api->setUserLicense ( $email, SERVER_IDENTIFIER );
									echo $alertMsg . $paymentLicense . '.';
								}
							} catch ( Exception $e ) {
								echo $e->getMessage ();
								break;
							}
						}
					} else {
						try {
							$subscription = Recurly_Subscription::get ( $paymentId );
							$subscription->cancel ();
							$subscription = Recurly_Subscription::get ( $paymentId );
							if ($subscription->state == 'canceled') {
								$api->updateUserPaymentConfig ( SERVER_IDENTIFIER, $paymentLicense, $email, $subscription->state );
								$api->setUserLicense ( $email, SERVER_IDENTIFIER );
								echo $alertMsg . $paymentLicense . '.';
							}
						} catch ( Exception $e ) {
							echo $e->getMessage ();
							break;
						}
					}
				} else {
					echo "Error: Incorrect email or password.";
				}
				break;
			case "isLoggedIn" :
				if (array_key_exists ( 'user_name', $_SESSION ) && ! empty ( $_SESSION ['user_name'] ) && array_key_exists ( 'user_pass', $_SESSION ) && ! empty ( $_SESSION ['user_pass'] )) {
					$userSettings = $api->getSettings ();
					echo $userSettings->settings->licenseType . '-' . $_SESSION ['user_name'];
				}
				break;

			case "refreshAccount":
				// if (isset ( $_POST ['i'] )) {
					// $i_val = intval ( $_POST ['i'] );
					// $accounts = new SimpleXMLElement ( $api->getAccountsData ( 'accounts' ) );
					// $acc_id = $accounts->analyticsAccounts->account [$i_val]->accountId;
                    // $resp = $api->refreshAnalyticsAccount( $acc_id );
					$resp = $api->refreshAnalyticsAccount( $_POST['accountID'] );

					echo ( string ) $resp->returnCode;
				// }
				break;

			case "newBlogUser" :
				if (isset ( $_POST ['name'] ) && isset ( $_POST ['email'] )) {
					$resp = $api->newBlogUser ( $_POST ['name'], $_POST ['email'] );
					echo json_encode ( $resp );
				} else {
					echo "Incorrect Data!";
				}
				break;
			case "setAccountExpired" :
				$i_val = intval ( $_POST ["i"] );
				$accounts = new SimpleXMLElement ( $api->getAccountsData ( 'accounts' ) );
				$acc_id = $accounts->analyticsAccounts->account [$i_val]->accountId;
				$resp = $api->setAccountExpired ( $acc_id );
				echo json_encode ( $resp );
				break;
			
            case "getFavoriteStreams":
				$resp = $api->getFavoriteStreams();
				echo json_encode( $resp );
				break;

			case "addFavoriteStream":
				if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) && isset( $_POST['streamID'] ) && isset( $_POST['streamName'] ) )
                {
					$resp = $api->addFavoriteStream( $_POST['accountID'], $_POST['profileID'], $_POST['streamID'], $_POST['streamName'], $_POST['selected']);
					echo json_encode ( $resp );
				}
                else echo "Incorrect Data!";

				break;

			case "removeFavoriteStream":
                // if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] ) && isset ( $_POST ['streamId'] ) && isset ( $_POST ['name'] ) && isset ( $_POST ['value'] )) {
				if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) && isset( $_POST['streamId'] ) && isset( $_POST['name'] ) && isset( $_POST['value'] )) 
                {
                    // $resp = $api->removeFavoriteStream ( $_POST ['i'], $_POST ['j'], $_POST ['streamId'], $_POST ['name'], $_POST ['value'] );
					$resp = $api->removeFavoriteStream( $_POST['accountID'], $_POST['profileID'], $_POST['streamId'], $_POST['name'], $_POST['value'], $_POST['selected'] );
					echo json_encode ( $resp );
				}

                else echo "Incorrect Data!";

				break;

			case "setFavoriteStream":

				if ( isset( $_POST['streams'] ) ) 
                {
					$resp = $api->setFavoriteStream( $_POST['streams'] );
					
                    echo json_encode( $resp );
				} 

                else echo "Incorrect Data!";
				
                break;

            case "setSearchStreams":

                if ( isset( $_POST['streams'] ) ) 
                {
                    $resp = $api->setSearchStream( $_POST['streams'] );
                    
                    echo json_encode( $resp );
                } 

                else echo "Incorrect Data!";
                
                break;

            case "setOutreachStreams":

                if ( isset( $_POST['streams'] ) ) 
                {
                    $resp = $api->setOutreachStream( $_POST['streams'] );
                    
                    echo json_encode( $resp );
                } 

                else echo "Incorrect Data!";
                
                break;

            case "setContentCuration":

                if ( isset( $_POST['streams'] ) ) 
                {
                    $resp = $api->setContentCuration( $_POST['streams'] );
                    
                    echo json_encode( $resp );
                } 

                else echo "Incorrect Data!";
                
                break;

			case "getGroupTargeting" :
                if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] )) {
                    $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
                    if ($acc_data == 'reload') {
                        echo "Reload page";
                    } else {
                        $resp = $api->getGroupTargeting ( $acc_data->user_at, $_POST ['group_id'] );
                        echo json_encode ( $resp );
                    }
                } 

				else if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] )) {
					$acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
					if ($acc_data == 'reload') {
						echo "Reload page";
					} else {
						$resp = $api->getGroupTargeting( $acc_data->user_at, $_POST['group_id'] );
						echo json_encode( $resp );
					}
				} 

                else echo "Incorrect Data!";

				break;
			case "getGroupPreview" :
                if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) )
                {
                    $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
				//if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] )) {
				//	$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
					if ($acc_data == 'reload') {
						echo "Reload page";
					} else {
						$resp = $api->getGroupPreview ( $acc_data->user_at, $_POST ['group_id'] );
						echo json_encode ( $resp );
					}
				} else {
					echo "Incorrect Data!";
				}
				break;
			case "getGroupCss" :
                if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) )
                {
                    $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
				//if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] )) {
				//	$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
					if ($acc_data == 'reload') {
						echo "Reload page";
					} else {
						$resp = $api->getGroupCss ( $acc_data->user_at, $acc_data->userId );
						echo json_encode ( $resp );
					}
				} else {
					echo "Incorrect Data!";
				}
				break;
			case "getAdsLineGraphData" :
                if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] )) {
                    $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
                    if ($acc_data == 'reload') {
                        echo "Reload page";
                    } else {
                        $resp = $api->getAdsLineGraphData ( $acc_data->user_at, $_POST ['group_id'], $_POST ['array'] );
                        echo json_encode ( $resp );
                    }
                } 
				else if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] )) {
					$acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
					if ($acc_data == 'reload') {
						echo "Reload page";
					} else {
						$resp = $api->getAdsLineGraphData( $acc_data->user_at, $_POST['group_id'], $_POST['array'] );
						echo json_encode ( $resp );
					}
				} 

                else echo "Incorrect Data!";

				break;
			case "getAdGroupsList" :
				if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] )) {
					$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
					if ($acc_data == 'reload') {
						echo "Reload page";
					} else {
						$resp = $api->getAdGroupsList ( $acc_data->user_at, $acc_data->userId );
						$resp ['id'] = ( string ) $acc_data->pageId;
						echo json_encode ( $resp );
					}
				} else {
					echo "Incorrect Data!";
				}
				break;
			case "getAdCompaignList" :
				if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] )) {
					$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
					if ($acc_data == 'reload') {
						echo "Reload page";
					} else {
						$resp = $api->getAdCompaignList ( $acc_data->user_at, $_POST ['array'] );
						echo json_encode ( $resp );
					}
				} else {
					echo "Incorrect Data!";
				}
				break;
			case "getAdGroupStat" :
				if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] )) {
					$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
					if ($acc_data == 'reload') {
						echo "Reload page";
					} else {
						$resp = $api->getAdGroupStat ( $acc_data->user_at, $acc_data->userId, $_POST ['array'], $_POST ['current'], $_POST ['compaign'], $_POST ['group'] );
						echo json_encode ( $resp );
					}
				} else {
					echo "Incorrect Data!";
				}
				break;
			case "getFBPosts" :
				if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] )) {
					$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
					if ($acc_data == 'reload') {
						echo "Reload page";
					} else {
						$resp = $api->getFBPosts ( $acc_data->page_at, $acc_data->pageId, $_POST ['next'] );
						echo json_encode ( $resp );
					}
				} else {
					echo "Incorrect Data!";
				}
				break;
			case "getFBPostsStats" :
				if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] )) {
					$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
					if ($acc_data == 'reload') {
						echo "Reload page";
					} else {
						$resp = $api->getFBPostsStats ( $acc_data->page_at, $_POST ['array'] );
						echo json_encode ( $resp );
					}
				} else {
					echo "Incorrect Data!";
				}
				break;
			case "getTWPosts" :
				if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] )) {
					$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
					if ($acc_data == 'reload') {
						echo "Reload page";
					} else {
						$connection = new TwitterOAuth ( CONSUMER_KEY, CONSUMER_SECRET, ( string ) $acc_data->user_at, ( string ) $acc_data->user_ts );
						$array = array (
								'user_id' => ( string ) $acc_data->userId,
								'count' => '50'
						);
						if (isset ( $_POST ['max_id'] )) {
							$array ['max_id'] = $_POST ['max_id'];
						}
						$result = $connection->get ( 'statuses/home_timeline', $array );
						echo json_encode ( $result );
					}
				} else {
					echo "Incorrect Data!";
				}
				break;
			case "getSampleFacebook" :
				if (isset ( $_POST ['accounts'] )) {
					$accounts = new SimpleXMLElement ( $api->getAccountsData ( 'accounts' ) );
					$accounts = $accounts->analyticsAccounts->account;
					echo json_encode ( $api->sampleAnalyticsFacebook ( $_POST ['accounts'], $accounts ) );
				}
				break;
			case "getAdsandPostFacebook":

                if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) && isset( $_POST['next'] ) )
                {
                    $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
                    
                    if ($acc_data == 'reload') echo "Reload page";

                    else
                    {
                        echo json_encode( $api->getSampleFacebook( $_POST['accountID'], $_POST['profileID'], $_POST['type'] ) );
                    }
                }

                else echo "Incorrect data";

				// if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] ) && isset ( $_POST ['type'] ))
    //             {
				// 	$accounts = new SimpleXMLElement ( $api->getAccountsData ( 'accounts' ) );
				// 	$accounts = $accounts->analyticsAccounts->account;
				// 	$i_val = intval ( $_POST ['i'] );
				// 	$j_val = intval ( $_POST ['j'] );
				// 	$accountId = $accounts [$i_val]->accountId;
				// 	$sampleId = $accounts [$i_val]->config [$j_val]->sampleId;
				// 	echo json_encode ( $api->getSampleFacebook ( $accountId, $sampleId, $_POST ['type'] ) );
				// }
				break;
			case "refreshAds" :
				if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) ) {

					echo json_encode ( $api->refreshAds( $_POST['accountID'], $_POST['profileID'] ) );
				}
				break;
			case "dataIsReadyCheck" :
				if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] )) {
					$accounts = new SimpleXMLElement ( $api->getAccountsData ( 'accounts' ) );
					$accounts = $accounts->analyticsAccounts->account;
					$i_val = intval ( $_POST ['i'] );
					$j_val = intval ( $_POST ['j'] );
					$accountId = $accounts [$i_val]->accountId;
					$sampleId = $accounts [$i_val]->config [$j_val]->sampleId;
					echo json_encode ( $api->dataIsReadyCheck ( $accountId, $sampleId ) );
				}
				break;
			case "getAnalyticsSampleData" :
				if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] ) && isset ( $_POST ['type'] )) {
					$accounts = new SimpleXMLElement ( $api->getAccountsData ( 'accounts' ) );
					$accounts = $accounts->analyticsAccounts->account;
					$i_val = intval ( $_POST ['i'] );
					$j_val = intval ( $_POST ['j'] );
					$accountId = $accounts [$i_val]->accountId;
					$sampleId = $accounts [$i_val]->config [$j_val]->sampleId;
					echo json_encode ( $api->getAnalyticsSampleIdDataByType ( $accountId, $sampleId, $_POST ['type'] ) );
				}
				break;
            case 'getGoogleAnalyticsRealTime' :            
                if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) )
                {
                    $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
                    
                    if ($acc_data == 'reload') echo "Reload page";
                    else
                    {               
                        $test = $api->getGoogleAnalyticsRealTime( $_POST['accountID'], $_POST['profileID'] );

                        $graphs = $test->analyticsAccounts->account->config->GoogleAnalyticsMetrics->GoogleRealTimeMetrics;
                        
                        if (isset ( $graphs->sample )) {
                            foreach ($graphs->sample as $tempa) {

                                $tempa = (array)$tempa;
                                $json [] = Array (
                                    'displayName' => ifNotArrayReturnArray($tempa['displayName']),
                                    'graphId' => ifNotArrayReturnArray($tempa['graphId']),
                                    'category' => ifNotArrayReturnArray($tempa['category']),
                                    'summaryLevel' => ifNotArrayReturnArray($tempa['summaryLevel']),
                                    'xText' => ifNotArrayReturnArray($tempa['xText']),
                                    'yText' => ifNotArrayReturnArray($tempa['yText']),
                                    'status' => ifNotArrayReturnArray($tempa['status']),
                                    'r' => ifNotArrayReturnArray($tempa['r']),
                                    //'nickName' => urldecode ( $accounts [$i]->nickName ),
                                    //'id_val' => $i,
                                    //'type' => $accounts [$i]->type,
                                    //'mail' => $mail,
                                    'display' => ( string ) $tempa['display'],
                                    't' => ifNotArrayReturnArray($tempa['t']),
                                    'graphMessage' => ifNotArrayReturnArray($tempa['graphMessage']),
                                    'handler' => ifNotArrayReturnArray($tempa['handler'])
                                );
                            }
                        }
                        echo json_encode ($json);
                    }
                }
                break;
                
            case "checkLogin" :
                if ($api->ifUserLogined ()) {
                    echo 'true';
                } else {
                    echo 'Reload page';
                }
                break;

			case "is_logged_in" :
                // if (!empty($_SESSION['AUTH_STATUS']) && $_SESSION['AUTH_STATUS'] == 1 && !empty($_SESSION['user_name']) && !empty($_SESSION['user_pass']))
                if ( $api->logged_in() )
                {
					echo 'true';
				} else {
					echo 'false';
				}
				break;

            case "internalManageUser" :
                if (isset ( $_POST ['message'] )){
                    try {
                        $response = $api->internalManageUser ($_SESSION['user_name'],$_SESSION['user_pass'],$_POST['message']);
                        echo $response;
                    } catch (Exception $e) {
                        echo "server error";
                    }
                }
                break;
            case "getInternalFullReport" :
                    try {
                        $response = $api->getInternalFullReport ($_SESSION['user_name'],$_SESSION['user_pass']);
                        echo $response;
                    } catch (Exception $e) {
                        echo "server error";
                    }
					break;
            case "internalAdminUpdatePassword" :
                if (isset ( $_POST ['newPassword'] )){
                    try {
                        $response = $api->internalAdminUpdatePassword ($_SESSION['user_name'],$_SESSION['user_pass'],$_POST['newPassword'],$_POST['hiddenManagedUser']);
                        echo $response;
                    } catch (Exception $e) {
                        echo "server error";
                    }
                }
                break;

            case "internalAdminSendWelcomeEmail" :
                if (isset ( $_POST ['hiddenManagedUser'] )){
                    try {
                        $response = $api->internalAdminSendWelcomeEmail ($_SESSION['user_name'],$_SESSION['user_pass'],$_POST['hiddenManagedUser']);
                        echo $response;
                    } catch (Exception $e) {
                        echo "server error";
                    }
                }
                break;
            case "internalAdminDeleteUser" :
                if (isset ( $_POST ['hiddenManagedUser'] )){
                    try {
                        $response = $api->internalAdminDeleteUser ($_SESSION['user_name'],$_SESSION['user_pass'],$_POST['hiddenManagedUser']);
                        echo $response;
                    } catch (Exception $e) {
                        echo "server error";
                    }
                }
                break;

            case "internalAdminSendAllUsersEmail" :
                    try {
                        $response = $api->internalAdminSendAllUsersEmail ($_SESSION['user_name'],$_SESSION['user_pass'],$_POST['adminSendAllUsersSubject'],$_POST['adminSendAllUsersText'],$_POST['sendToGroup'],$_POST['emailSpecificUsersList']);
                        echo $response;
                    } catch (Exception $e) {
                        echo "server error";
                    }
                break;
            case "internalMarketingSendEmail" :
                    try {
                        $response = $api->internalMarketingSendEmail ($_SESSION['user_name'],$_SESSION['user_pass'],$_POST['adminSendAllUsersSubject'],$_POST['adminSendAllUsersText'],$_POST['sendToGroup'],$_POST['emailSpecificUsersList'],$_POST['onlyNonVerified'],$_POST['onlyNonLoggedIn'],$_POST['daysOnlyNonLoggedIn'],$_POST['onlyEmailSuffix'],$_POST['emailSuffix'],$_POST['sendToAll'],$_POST['onlyNoAccounts'],$_POST['onlyNonAccountGA'],$_POST['onlyNonAccountFB'],$_POST['onlyNonAccountLN'],$_POST['onlyNonAccountTW'],$_POST['onlyNonAccountWP'],$_POST['onlyNonAccountIG'],$_POST['onlyNonAccountYT'],$_POST['onlyPlanTypeFree'],$_POST['onlyPlanTypeBasic'],$_POST['onlyPlanTypePremier'],$_POST['onlyNonLifetimePost'],$_POST['onlyNonPost'],$_POST['daysOnlyNonPost']);
                        echo $response;
                    } catch (Exception $e) {
                        echo "server error";
                    }
                break;
           case "internalMarketingRefreshStatus" :
                    try {
                        $response = $api->internalMarketingRefreshStatus ($_SESSION['user_name'],$_SESSION['user_pass']);
                        echo $response;
                    } catch (Exception $e) {
                        echo "server error";
                    }
                break;

            case "getInFeed":
                // if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] ) && isset ( $_POST ['next'] )) {
                if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) && isset( $_POST['next'] ) )
                {
                    // $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
                    $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
                    
                    if ($acc_data == 'reload') echo "Reload page";
                    else
                    {
                        echo $api->getNewsFeedMostRecentServer( $acc_data->accountId, $acc_data->sampleId, $_POST['stream'], $_POST['next'], "");
                    }
                } else {
                    echo "Incorrect data";
                }
                break;

            case "getInMyMedia":
                // if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] ) && isset ( $_POST ['next'] )) {
                if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) && isset( $_POST['next'] ) )
                {
                    // $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
                    $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
                    
                    if ($acc_data == 'reload') echo "Reload page";
                    else 
                    {
                        echo $api->getNewsFeedMostRecentServer( $acc_data->accountId, $acc_data->sampleId, $_POST['stream'], $_POST['next'], "");
                    }
                } 
                else echo "Incorrect data";
                break;

            case "getGPStream" :
            	//if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] ) && isset ( $_POST ['next'] )) {
                if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) )
                {
            		//$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
                    $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
            		if ($acc_data == 'reload') {
            			echo "Reload page";
            		} else {
            			echo $api->getGooglePlusStream( $acc_data->accountId, $acc_data->sampleId, $_POST ['stream'], $_POST ['next'] );
            		}
            	} else {
            		echo "Incorrect data";
            	}
                break;
            case "gp_user_info":
                if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) )
                {
                    //$acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
                    $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
                    if ($acc_data == 'reload') {
                        echo "Reload page";
                    } else {
                        echo $api->getGooglePlusStream( $acc_data->accountId, $acc_data->sampleId, "gp_user_info", $_POST['user_id'] );
                    }
                } else {
                    echo "Incorrect data";
                }
                break;
            case "addInComment" :
                //if (isset ( $_POST ["post_id"] ) && isset ( $_POST ['i'] ) && isset ( $_POST ["j"] )) {
                //  $acc_data = $api->getAccountData ( $_POST ['i'], $_POST ['j'] );
                    // if ($acc_data == 'reload') {
                    //     echo "Reload page";
                    // } else {
                    // }
                if (!$api->ifUserLogined()) 
                {
                    echo "Reload page";
                } 
                else if (isset ( $_POST ["post_id"] ) && isset ( $_POST ['accountID'] ) && isset ( $_POST ["profileID"] )) {
                    
                    if (isset ( $_POST ['message'] )) {
                        echo $api->publishSocialStreamObject ( "sendIGComment", $_POST ["post_id"], $_POST ['message'],"text");
                    } else {
                        echo "Please enter your message";
                    }
                    
                } else {
                    echo 'Incorrect data!';
                }
                break;
                
            case 'likeINPost' :
                //if (isset ( $_POST ["post_id"] ) && isset ( $_POST ["i"] ) && isset ( $_POST ['j'] )) {
                if (!$api->ifUserLogined()) 
                {
                    echo "Reload page";
                } 
                else if ( isset( $_POST["post_id"] ) && isset( $_POST['accountID'] ) && isset( $_POST['profileID'] )) 
                {
                    if ($_POST ['like'] == "true") 
                    {
                        echo $api->publishSocialStreamObject ( "sendIGLike", $_POST ["post_id"], "", "message" );
                    } 
                    else 
                    {
                        echo $api->publishSocialStreamObject ( "removeIGLike", $_POST ["post_id"], "", "message" );
                    }
                }
                else 
                {
                    echo "Incorrect data!";
                }
                break;
            case "getInUserProfile" :
                
                if ( !$api->ifUserLogined() ) echo "Reload page";

                if ( isset( $_POST['i'] ) && isset( $_POST['j'] ) && isset( $_POST['user_id'] ) )
                {
                    if (isset($_POST['account']) && $_POST['account'] == 'true')
                    {
                        $result = json_decode($api->getSocialStreamsCommand ( "userInfo", 'user_id', $_POST ["user_id"] ))->counts;

                        $result->i = $_POST ['i'];
                        $result->j = $_POST ['j'];
                        $result->type = "in";
                        echo json_encode($result);
                    }

                    else echo $api->getSocialStreamsCommand ( "userInfo", 'user_id', $_POST ["user_id"] );
                }

                else if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) && isset( $_POST['user_id'] ) )
                {
                    if (isset($_POST['account']) && $_POST['account'] == 'true')
                    {
                        $info = $api->getSocialStreamsCommandID( $_POST['accountID'], $_POST['profileID'], "userInfo", 'user_id', $_POST["user_id"] );

                        $result = json_decode( $info )->counts;

                        $result->type = "in";

                        echo json_encode($result);
                    }

                    else echo $api->getSocialStreamsCommandID ( $_POST['accountID'], $_POST['profileID'], "userInfo", 'user_id', $_POST["user_id"] );
                }

                else echo "Incorrect data";
                
                break;
            case "getInLikes" :
                if ( !$api->ifUserLogined() ) echo 'reload';

                elseif ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) )
                {
                    echo $api->getSocialStreamsCommandID ( $_POST['accountID'], $_POST['profileID'], "igLikes", 'media_id', $_POST ["post_id"] );
                }

                else echo 'Incorrect data';
                // if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] ) && isset ( $_POST ['post_id'] )) {
                //     if (!$api->ifUserLogined()) {
                //         echo "Reload page";
                //     } else {
                //         echo $api->getSocialStreamsCommandID ( "igLikes", 'media_id', $_POST ["post_id"] );
                //     }
                // } else {
                //     echo "Incorrect data";
                // }
                break;
            case "getInComments" :
                if ( !$api->ifUserLogined() ) echo 'reload';

                elseif ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) )
                {
                    echo $api->getSocialStreamsCommandID ( $_POST['accountID'], $_POST['profileID'], "igComments", 'media_id', $_POST ["post_id"] );
                }

                else echo 'Incorrect data';
               // if (isset ( $_POST ['i'] ) && isset ( $_POST ['j'] ) && isset ( $_POST ['post_id'] )) {
                    //if (!$api->ifUserLogined()) {
                    //    echo "Reload page";
                    //} else {
                    //    echo $api->getSocialStreamsCommandID ( "igComments", 'media_id', $_POST ["post_id"] );
                    //}
                //} else {
                    //echo "Incorrect data";
                //}
                break;
            case 'followInUser' :
                //if (isset ( $_POST ["user_id"] ) && isset ( $_POST ["i"] ) && isset ( $_POST ['j'] )) {
            if (isset ( $_POST ["user_id"] ) && isset ( $_POST ["accountID"] ) && isset ( $_POST ['profileID'] )) {
                    if (!$api->ifUserLogined()) {
                        echo "Reload page";
                    } else {
                        if ($_POST ['follow'] == "follow") {
                            echo $api->publishSocialStreamObject ( "sendIGRelationship", $_POST ["user_id"], "unfollow", "action" );
                        } else {
                            echo $api->publishSocialStreamObject ( "sendIGRelationship", $_POST ["user_id"], "follow", "action" );
                        }
                    }
                } else {
                    echo "Incorrect data!";
                }
                break;
			
            case 'getWidgetUser':
                $user = $api->wix_getWidgetUser( $_REQUEST["instanceID"] );
                echo json_encode( $user );
                break;
                
            case 'getWidgetFeeds':
                // echo $api->getWidgetFeeds( $_SESSION["user_name"], $_SESSION["user_pass"], $_POST['premium'] );
                // ECErrorHandler::getWidgetFeeds_log( $_SESSION["user_name"] .' ::: '. $_SESSION["user_pass"] .' ::: '. $_POST['premium'] );
                echo $api->getWidgetFeeds( $_REQUEST["instanceID"], $_REQUEST['premium'], $_REQUEST['editor_view'] );
                break;
                                
			case 'setWixEditorFeeds':

                $payload = array_to_xml( $_POST['data'] );

                $request = simplexml_load_string('<request>'.$payload.'</request>'); 

				echo $api->setWixEditorFeeds( $request, $_POST['ID'], $_POST['layout'], $_POST['autoscroll'] );

				break;
                
            case 'setDefaultGroupId':
                if ( isset( $_POST["accountID"] ) && isset( $_POST['profileID'] ) && isset( $_POST['defaultGroupId'] ) && isset( $_POST['network'] ) )
                {
                    $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );

                    if ( $acc_data == 'reload') echo "Reload page";
                    
                    else
                    {
                        echo json_encode( $api->setDefaultGroupId($acc_data->accountId, $acc_data->sampleId, $_POST['network']) );
                    }
                }
                break;

            case 'setDefaultCompanyId':
                if ( isset( $_POST["accountID"] ) && isset( $_POST['profileID'] ) && isset( $_POST['defaultCompanyId'] ) )
                {
                    $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );

                    if ( $acc_data == 'reload') echo "Reload page";
                    
                    else
                    {
                        echo json_encode( $api->setDefaultCompanyId( $acc_data->accountId, $acc_data->sampleId, $_POST['defaultCompanyId'] ) );
                    }
                }
                break;

            case 'setDefaultChannelId':
                if ( isset( $_POST["accountID"] ) && isset( $_POST['profileID'] ) && isset( $_POST['defaultChannelId'] ) )
                {
                    $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );

                    if ( $acc_data == 'reload') echo "Reload page";
                    
                    else
                    {
                        echo json_encode( $api->setDefaultChannelId( $acc_data->accountId, $acc_data->sampleId, $_POST['defaultChannelId'] ) );
                    }
                }
                break;

            case 'hashPassword':
                echo Utilities::SHA1HashPassword( $_POST['password'] );
                break;

            case 'getYouTubeFeed':
    
                if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) )
                {
                    
                    if ($acc_data == 'reload') echo "Reload page";

                    else 
                    {
                        if ( $_POST['stream'] == 'yt_comments' ) 
                            echo $api->getNewsFeedMostRecentServer( $_POST['accountID'], $_POST['profileID'], $_POST['stream'], $_POST['nextToken'], $_POST['video_id']);
                        else if ( $_POST['stream'] == 'yt_channelVideos' || $_POST['stream'] == 'yt_mySubscription' ) 
                            echo $api->getNewsFeedMostRecentServer( $_POST['accountID'], $_POST['profileID'], $_POST['stream'], $_POST['nextToken'], $_POST['channel_id']);
                        else
                            echo $api->getNewsFeedMostRecentServer( $_POST['accountID'], $_POST['profileID'], $_POST['stream'], $_POST['nextToken'], "");   
                       
                    }
                } 
                else echo "Incorrect data";

                break;

            case 'addYTComment':
                
                if ( isset( $_POST["post_id"] ) && isset( $_POST['accountID'] ) && isset( $_POST["profileID"] ))
                {
                    echo $re = $api->publishSocialStreamObject("addYTComment",$_POST ['post_id'],$_POST["comment"],"comment");
                }

                break;

            case 'likeYT':
                
                if ( isset( $_POST["post_id"] ) && isset( $_POST['accountID'] ) && isset( $_POST["profileID"] ))
                {
                    echo $re = $api->publishSocialStreamObject("likeYT",$_POST ['post_id'],$_POST["like"],"like");
                }

                break;

            case 'getYTChannelInfo':

                $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );

                if ($acc_data == 'reload') echo "Reload page";

                else 
                {
                    if ( isset( $_POST["user_id"] )) {
                        echo $api->getNewsFeedMostRecentServer( $_POST['accountID'], $_POST['profileID'], 'getYTChannelInfo', "", $_POST["user_id"]);
                    } else {
                        echo $api->getNewsFeedMostRecentServer( $_POST['accountID'], $_POST['profileID'], 'getYTChannelInfo', "", "");   
                    } 
                }

                break;

            case 'getTWConversation':
                $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
                if ($acc_data == 'reload') {
                    echo "Reload page";
                } else {
                    if ( isset( $_POST["id_str"] )) {
                        echo $api->getTWConversationFromServer( $acc_data->userId, $acc_data->accountId, $acc_data->user_at, $acc_data->user_ts, $_POST["id_str"] );
                    }
                }
                break;

            case 'getSampleDataRandomInterval':

                if ( !$api->ifUserLogined() ) echo 'reload';

                elseif ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) )
                {
                    $account = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );

                    if ( isset( $account->accountId ) && isset( $account->sampleId ) )
                    {
                        //$has_cook = false;
                        //$hash_code = md5 ( $account->accountId . $account->sampleId );
                        //$time = null;
                        
                        
                        
                        $json = Array ();
                        
                        

                        //if ( !$has_cook )
                        //{
                            $el = $api->getAnalyticsSampleIdDataRandomInterval( $account->accountId, $account->sampleId, $_POST['from'], $_POST['to'] );
                            $compressed = base64_decode( $el->archive );
                            $compressed = substr( $compressed, 4 );
                            file_put_contents( sys_get_temp_dir() . "/archive_" . $_SESSION['user_name'] . "_analytics.gz", $compressed );
                            $gz = gzopen( sys_get_temp_dir() . "/archive_" . $_SESSION['user_name'] . "_analytics.gz", "r" );
                            $response = "";
                            
                            while ( !gzeof( $gz ) ) $response .= gzgets( $gz, 255 );

                            gzclose( $gz );
                            
                            $response = urldecode( $response );

                            $xml = simplexml_load_string( $response, 'SimpleXMLElement', LIBXML_COMPACT | LIBXML_PARSEHUGE );
                            $graph_accounts = $xml->analyticsAccounts->account;
                            $type = $graph_accounts->type;
                            
                            switch ( $type ):
                                case 'Facebook':
                                    $graphs = $xml->analyticsAccounts->account->config->FacebookAnalyticsMetrics->sample;
                                    break;
                                case 'Twitter':
                                    $graphs = $xml->analyticsAccounts->account->config->TwitterAnalyticsMetrics->sample;
                                    break;
                                /*case 'YouTube':
                                    $graphs = $xml->analyticsAccounts->account->config->YouTubeAnalyticsMetrics->sample;
                                    break;
                                case 'Linkedin':
                                    $graphs = $xml->analyticsAccounts->account->config->LinkedInAnalyticsMetrics->sample;
                                    break;*/
                                default:
                                    return null;
                                    break;
                            endswitch;

                            foreach ( $graphs as $item )
                            {
                                $item_data = array (
                                        'status' => $item->status,
                                        'wait_message' => $xml->analyticsAccounts->waitMessage,
                                        'startDate' => $item->startDate,
                                        'endDate' => $item->endDate,
                                        'display' => (string) $item->display
                                );

                                $item_data['r'] = new stdClass();
                                
                                if ( is_string( $item->r ) ) $item_data['r']->{'0'} = $item->r;

                                else
                                {
                                    $y = 0;
                                    foreach ($item->r as $key => $value)
                                    {
                                        $item_data['r']->$y = (string) $value;
                                        $y++;
                                    }
                                }

                                if ( is_string( $item->displayName ) ) $item_data['displayName'] = (object) array('0' => $item->displayName );
                                else $item_data['displayName'] = $item->displayName;

                                if ( is_string( $item->graphId ) ) $item_data['graphId'] = (object) array('0' => $item->graphId );
                                else $item_data['graphId'] = $item->graphId;
                                
                                if ( is_string( $item->xText ) ) $item_data['xText'] = (object) array('0' => $item->xText );
                                else $item_data['xText'] = $item->xText;
                                
                                if ( is_string( $item->yText ) ) $item_data['yText'] = (object) array('0' => $item->yText );
                                else $item_data['yText'] = $item->yText;
                                
                                if ( isset( $item->t ) )
                                {
                                    if ( is_string( $item->t ) ) $item_data['t'] = (object) array('0' => $item->t );
                                    else $item_data['t'] = $item->t;
                                }
                                else $item_data['t'] = new stdClass();

                                if ( SYSTEM_TYPE == 'QA' && isset( $item->actualSampleTime ) )
                                {
                                    if ( is_string( $item->actualSampleTime ) ) $item_data['actualSampleTime'] = (object) array('0' => $item->actualSampleTime );
                                    else $item_data['actualSampleTime'] = $item->actualSampleTime;
                                }

                                if ( isset( $item->graphMessage ) )
                                {
                                    if ( is_string( $item->graphMessage ) ) $item_data['graphMessage'] = (object) array('0' => $item->graphMessage );
                                    else $item_data['graphMessage'] = $item->graphMessage;
                                }
                                else $item_data['graphMessage'] = new stdClass();

                                if ( isset( $item->handler ) )
                                {
                                    if ( is_string( $item->handler ) ) $item_data['handler'] = (object) array('0' => $item->handler );
                                    else $item_data['handler'] = $item->handler;
                                }
                                else $item_data['handler'] = new stdClass();

                                $category = ( string ) $item->category;  
                                
                                if ( $category == "") $category = "Twitter";

                                $json[ $category ][ (string) $item->summaryLevel ][] = $item_data;
                            }
                            
                            system( "rm -r " . sys_get_temp_dir() . "/archive_" . $_SESSION ['user_name'] . "_analytics.gz" );

                        //}

                        //$json["hash_tag"] = $hash_code;
                        //$json["has_cook"] = $has_cook;
                        $json = json_encode( $json );
                        echo $json;
                    }
                    
                    else echo 'reload';
                }
                break;

            case 'getShopifyProducts':

                if ( !$api->ifUserLogined() ) echo 'reload'; 

                //("americannautical.myshopify.com","0ec6484a787977d04bfa7884ae2d2b38", 'ccb1f967a932f6fb8e7b56fe264635d5', '2c8de20e59056d14425cb70045306a2e');
                //
                $url = '/admin/products.json?limit=50&page=' . $_POST['page'];

                echo $api->shopifyRequest( $_POST['shop'], $_POST['access_token'], 'GET', $url, array('published_status'=>'any') );

                break;

            case 'getShopifyProductsCount':

                if ( !$api->ifUserLogined() ) echo 'reload'; 

                echo $api->shopifyRequest( $_POST['shop'], $_POST['access_token'], 'GET', '/admin/products/count.json', array('published_status'=>'any') );

                break;

            case 'searchShopifyProducts':

                if ( !$api->ifUserLogined() ) echo 'reload'; 

                $params = array();
                $params['view'] = 'json.eclincher';
                $params['type'] = 'product';
                $params['q'] = $_POST['query'];

                echo $api->shopifyRequest( $_POST['shop'], $_POST['access_token'], 'GET', '/search', $params );

                break;

            case 'getShopifyThemes':

                if ( !$api->ifUserLogined() ) echo 'reload'; 

                echo $api->shopifyRequest( $_POST['shop'], $_POST['access_token'], 'GET', '/admin/themes.json' );

                break; 

            case 'putShopifyTemplate':

                if ( !$api->ifUserLogined() ) echo 'reload';

                $url = '/admin/themes/' . $_POST['theme_id'] . '/assets.json';

                $params = array();
                $asset = array();
                $asset['key'] = $_POST['key'];
                $asset['value'] = $_POST['value'];
                $params['asset'] = $asset;

                echo $api->shopifyRequest( $_POST['shop'], $_POST['access_token'], 'PUT', $url, $params );

                break;   

            case 'getImageData':

                if ( isset( $_POST['images'] )) {
                    echo $api->getImageData( $_POST['images'] );
                }

                break;

            case 'getBigcommerceProductsCount':

                if ( !$api->ifUserLogined() ) echo 'reload';

                echo $api->getBigcommerceProductsCount( $_POST['store'], $_POST['client_id'], $_POST['access_token'] );

                break;  

            case 'getWeeblyRequest':

                if ( !$api->ifUserLogined() ) echo 'reload';

                echo $api->weeblyRequest( $_POST['user_id'], $_POST['site_id'], $_POST['access_token'], $_POST['method'], $_POST['suffix_url'] );

                break;

            case 'getEtsyRequest':

                if ( !$api->ifUserLogined() ) echo 'reload';

                if ( isset( $_POST['access_token_secret'] ) ) echo $api->etsyRequest( $_POST['shop'], $_POST['access_token'], $_POST['access_token_secret'], $_POST['method'], $_POST['suffix_url'] );

                else echo $api->etsyRequest( $_POST['shop'], $_POST['access_token'], NULL, $_POST['method'], $_POST['suffix_url'] );

                break;  

            case 'getLexityRequest':

                if ( !$api->ifUserLogined() ) echo 'reload';

                echo $api->lexityRequest( $_POST['shop'], $_POST['access_token'], $_POST['method'], $_POST['suffix_url'] );

                break;

            case 'getBigcommerceProducts':

                if ( !$api->ifUserLogined() ) echo 'reload';

                echo $api->getBigCommerceProducts( $_POST['store'], $_POST['client_id'], $_POST['access_token'], $_POST['page'] );

                break;

            case 'ec_delete_a_user_based_on_their_email_and_password_1029384756':

                // if ( isset( $_POST['username'] ) && isset( $_POST['password'] ) ) echo $api->deleteUser( $_POST['username'], Utilities::SHA1HashPassword( $_POST['password'] ) );
                if ( isset( $_POST['username'] ) && isset( $_POST['password'] ) ) echo $api->deleteUser( $_POST['username'], $_POST['password'] );

                else echo 'error';

                break;

            case 'addSearchStream':

                if ( !$api->ifUserLogined() ) echo 'reload';

                if ( isset( $_POST['profileID'] ) && isset( $_POST['parameters'] ) )
                    echo $api->addSearchStream( $_POST['accountID'], $_POST['profileID'], $_POST['parameters'] );

                else echo $api->addSearchStream( $_POST['accountID'] );

                break;

            case 'editSearchStream':

                if ( !$api->ifUserLogined() ) echo 'reload';

                if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) && isset( $_POST['id'] ) ) {

                    if ( isset( $_POST['parameters'] ) && isset( $_POST['name'] ) ) 
                    {
                        echo $api->editSearchStream( $_POST['id'], $_POST['accountID'], $_POST['profileID'], $_POST['parameters'], $_POST['name'] );
                    }
                    else echo $api->editSearchStream( $_POST['id'], $_POST['accountID'], $_POST['profileID'] );
                }

                else echo 'error';

                break;

            case 'getSearchStreams':

                if ( !$api->ifUserLogined() ) echo 'reload';

                echo $api->getSearchStreams();

                break;

            case 'removeSearchStream':

                if ( !$api->ifUserLogined() ) echo 'reload';

                if ( isset( $_POST['id'] )) echo $api->removeSearchStream( $_POST['id'] );

                else echo 'error';

                break;

            case 'verifyGPLoginAndPages':

                if ( !$api->ifUserLogined() ) echo 'reload';

                if ( isset( $_POST['accountID'] ) ) {

                    //$account = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );

                    if ( isset( $_POST['url'] )) echo $api->verifyGPLoginAndPages( $_POST['accountID'], $_POST['includeLogin'], $_POST['includeProfile'], $_POST['text2'], $_POST['encText2'], $_POST['url'] );

                    else echo $api->verifyGPLoginAndPages( $_POST['accountID'], $_POST['includeLogin'], $_POST['includeProfile'], $_POST['text2'], $_POST['encText2'] );
                }

                else echo 'error';

                break;

            case 'setAccountData':
                if ( !$api->ifUserLogined() ) echo 'reload';

                if ( isset( $_POST['accountID'] ) ) {
                     echo $api->setAccountData( $_POST['type'], $_POST['accountID'], $_POST['includeLogin'], $_POST['includeProfile'], $_POST['text2'], $_POST['encText2'], $_POST['encTextUser'] );
                }
                else echo 'error';

                break;

            case 'getAccountEmailPassword':
                if ( !$api->ifUserLogined() ) echo 'reload';
                if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) ) {
                    $account = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
                    echo $api->getAccountDecryptedPassword( $account );
                }
                else echo 'error';
                break;

            case 'addGPPage':
                if ( !$api->ifUserLogined() ) echo 'reload';
                if ( isset( $_POST['accountID']) && isset( $_POST['pages'] ) ) {
                    echo $api->addGPPage( $_POST['accountID'], $_POST['pages'] );
                }
                else echo 'error';
                break;

            case 'deleteGPPage':
                if ( !$api->ifUserLogined() ) echo 'reload';
                if ( isset( $_POST['accountID']) && isset( $_POST['pages'] ) ) {
                    echo $api->deleteGPPage( $_POST['accountID'], $_POST['pages'] );
                }
                else echo 'error';
                break;

            case 'verifyPinterestLoginAndBoards':
                if ( !$api->ifUserLogined() ) echo 'reload';
                echo $api->verifyPinterestLoginAndBoards( $_POST['text2'], $_POST['encText2'], $_POST['encTextUser'] );
                break;

            case 'addPinterestAccount':
                if ( !$api->ifUserLogined() ) echo 'reload';
                echo $api->addPinterestAccount('1', $_POST['text2'], $_POST['encText2'], $_POST['username'] );

                break;

            case 'getGeocode':
                if ( !$api->ifUserLogined() ) echo 'reload';
                if ( isset( $_POST['address']) ) {
                    echo $api->getGeocode( $_POST['address'] );
                }
                else echo 'error';

                break;
                
            case 'updatePIBoards':
                if ( !$api->ifUserLogined() ) echo 'reload';
                echo $api->updatePIBoards( $_POST['accountID'] );
                break;

            case 'getFacebookPermissions':
                if ( !$api->ifUserLogined() ) echo 'reload';
                if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) ) {
                    $account = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
                    echo json_encode( $api->getFacebookPermissions( $account->nickName, $account->userId, $account->user_at ) );
                }
                else echo 'error';
                break;

            case 'setAccountsEvents':

                if ( isset( $_POST['data'] ) ) echo $api->setAccountsEvents( $_POST['data'] );

                else echo 'error';

                break;

            case 'addOutreachStream':

                if ( !$api->ifUserLogined() ) echo 'reload';

                if ( isset( $_POST['profileID'] ) && isset( $_POST['parameters'] ) )
                    echo $api->addOutreachStream( $_POST['accountID'], $_POST['profileID'], $_POST['parameters'] );

                else echo 'error';
                //else echo $api->addOutreachStream( $_POST['accountID'] );

                break;

            case 'getOutreachStreams':

                if ( !$api->ifUserLogined() ) echo 'reload';

                echo $api->getOutreachStreams();

                break;

            case 'removeOutreachStream':

                if ( !$api->ifUserLogined() ) echo 'reload';

                if ( isset( $_POST['id'] )) echo $api->removeOutreachStream( $_POST['id'] );

                else echo 'error';
                
                break;

            case 'editOutreachStream':

                if ( !$api->ifUserLogined() ) echo 'reload';

                if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) && isset( $_POST['id'] ) && isset($_POST['parameters']) ) 
                {
                    echo $api->editOutreachStream( $_POST['id'], $_POST['accountID'], $_POST['profileID'], $_POST['parameters'] );
                }

                else echo 'error';

                break;

            case 'getUserEvents':

                if ( !$api->ifUserLogined() ) echo 'reload';
                
                echo $api->getUserEvents( $_POST['startTime'], $_POST['endTime'], $_POST['request_action'], $_POST['maxEvents'], $_POST['profileIds'], $_POST['tags'], $_POST['types'], $_POST['only_completed'], $_POST['ecRead'], $_POST['searchText']);

                break;

            case 'setUserEvents':

                if ( !$api->ifUserLogined() ) echo 'reload';
                
                echo $api->setUserEvents( $_POST['events'], $_POST['tags'], $_POST['markAll']);

                break; 

            case 'getBloggerPosts':

                if ( !$api->ifUserLogined() ) echo 'reload';
                
                if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) )
                {
                    echo $api->getBloggerPosts( $_POST['accountID'], $_POST['profileID'], "bl_all", $_POST['next'] );    
                }
                else echo 'error';

                break; 

            case "bl_user_info":
                if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) )
                {
                    $acc_data = $api->getAccountDataByID( $_POST['accountID'], $_POST['profileID'] );
                    if ($acc_data == 'reload') {
                        echo "Reload page";
                    } else {
                        echo $api->getBloggerPosts( $_POST['accountID'], $_POST['profileID'], "bl_user_info", $_POST['user_id'] );
                    }
                } else {
                    echo "Incorrect data";
                }
                break;

            case 'deleteGPPost':

                if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) && isset( $_POST['post_id'] ) )
                {
                    echo $api->deleteSocialStreamPost( $_POST['accountID'], $_POST['profileID'], $_POST['post_id'], "googleplus" );
                } else {
                    echo "Incorrect data";
                }

                break;

            case 'getGPComments':

                if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) && isset( $_POST['post_id'] ) )
                {
                    echo $api->getSocialStreamPostComments( $_POST['accountID'], $_POST['profileID'], $_POST['post_id'] );
                } else {
                    echo "Incorrect data";
                }

                break;

            case 'deleteGPComment':

                if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) && isset( $_POST['comment_id'] ) )
                {
                    echo $api->deleteSocialStreamComment( $_POST['accountID'], $_POST['profileID'], $_POST['comment_id'], "googleplus" );
                } else {
                    echo "Incorrect data";
                }

                break;

            case 'updateGPComment':

                if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) && isset( $_POST['comment_id'] ) && isset( $_POST['content'] ))
                {
                    echo $api->updateSocialStreamComment( $_POST['accountID'], $_POST['profileID'], $_POST['comment_id'], $_POST['content'], "googleplus" );
                } else {
                    echo "Incorrect data";
                }

                break; 

            case 'updateGPPost':

                if ( isset( $_POST['accountID'] ) && isset( $_POST['profileID'] ) && isset( $_POST['id'] ) && isset( $_POST['content'] ))
                {
                    echo $api->updateSocialStreamPost( $_POST['accountID'], $_POST['profileID'], $_POST['id'], $_POST['content'], "googleplus", $_POST['link'] );
                } else {
                    echo "Incorrect data";
                }

                break; 

            case 'addGooglePlusComment':
                
                if ( isset( $_POST["post_id"] ) && isset( $_POST['accountID'] ) && isset( $_POST["profileID"] ))
                {
                    echo $re = $api->publishSocialStreamObject("addGooglePlusComment",$_POST ['post_id'],$_POST["comment"],"comment");
                }

                break; 

            case 'saveNotification':
                
                $notifications = MultiCache::cache_get('notifications');

                $notifications = is_array($notifications) ? $notifications: array();

                $id = time();
                $notifications[$id] = array('id' => $id, 'content' => $_POST['comments'], 'status' => $_POST['status']);

                MultiCache::cache_set('notifications', $notifications, (60*60*24*7));

                $notifications = array_reverse($notifications);

                $data = array('returnCode' => 'SUCCESS', 'notifications' => $notifications);
                
                echo json_encode($data);
                break; 

            case 'listNotification':
                
                $notifications = MultiCache::cache_get('notifications');

                $notifications = is_array($notifications) ? $notifications: array();

                //remove if data is older than 7days
                foreach ($notifications as $key => $val) 
                {
                    if( check_date_diff(time(), $val['id']) )
                        unset($notifications[$key]);
                }

                $notifications = array_reverse($notifications);

                $data = array('returnCode' => 'SUCCESS', 'notifications' => $notifications);
                
                echo json_encode($data);

                break;

            case 'deleteNotification':
                
                $notifications = MultiCache::cache_get('notifications');

                $notifications = is_array($notifications) ? $notifications: array();

                $id = $_POST['id'];
                if( isset($notifications[$id]) )
                    unset($notifications[$id]);

                MultiCache::cache_set('notifications', $notifications, (60*60*24*7));

                $notifications = array_reverse($notifications);

                $data = array('returnCode' => 'SUCCESS', 'notifications' => $notifications);
                
                echo json_encode($data);

                break;

            case 'changeNotificationStatus':
                
                $notifications = MultiCache::cache_get('notifications');

                $notifications = is_array($notifications) ? $notifications: array();

                $id = $_POST['id'];
                $new_id = time();

                if( isset($notifications[$id]) )
                {
                    $notifications[$id]['status'] = $_POST['status'];
                    $notifications[$id]['id'] = $new_id;
                    $notifications[$new_id] = $notifications[$id];
                    unset($notifications[$id]);
                }

                MultiCache::cache_set('notifications', $notifications, (60*60*24*7));
                
                $notifications = array_reverse($notifications);

                $data = array('returnCode' => 'SUCCESS', 'notifications' => $notifications);
                
                echo json_encode($data);

                break;

            case 'getNotifications':

                $notifications = MultiCache::cache_get('notifications');

                $notifications = is_array($notifications) ? $notifications: array();

                //echo '<pre>';print_r($_SESSION);die;
                $lni = getLastNotificationId($_SESSION['user_name']);

                $notifications = array_reverse($notifications);
                //echo '<pre>'; print_r($notifications);
                $unseen_count = 0;
                $tmp = array();
                foreach ($notifications as $key => $val) 
                {
                    //remove if data is older than 7days
                    if( check_date_diff(time(), $val['id']) )
                    {
                        unset($notifications[$key]);
                        continue;
                    }

                    if( !(int)$val['status'] )
                        continue;

                    if((int)$val['id']>(int)$lni)
                        $unseen_count++;

                    $tmp[$key] = $val;
                }
                
                if(count($tmp) === 1)
                {
                    $tmp[] = array_shift($tmp);
                }

                $data = array('returnCode' => 'SUCCESS', 'notifications' => $tmp, 'unseen_count' => $unseen_count,'lni' => $lni);
                echo json_encode($data);

                break; 

            case 'setLastNotificationId':
                $id = $_POST['id'];
                if( (int)$id )
                {
                    $lastNotificationId = getLastNotificationId();

                    $username = $_SESSION['user_name'];
                    $lastNotificationId[$username] = $_POST['id'];

                    MultiCache::cache_set('lastNotificationId', $lastNotificationId, (60*60*24*7));

                    $data = array('returnCode' => 'SUCCESS');
                }
                else
                {
                    $data = array('returnCode' => 'FAIL', 'error' => 'Invalid notification ID.');
                }
                
                echo json_encode($data);

                break;

            // case "addUserQueue":
            case "addUserQueue":
                
                $payload = array_to_xml( $_POST['data'] );

                $request = simplexml_load_string('<request>'.$payload.'</request>'); 

                $saved = $api->addUserQueue( $request );

                echo json_encode( $saved );

                break;

            // case "addSavedUrls":
            case "getUserQueues":

                $queues = $api->getUserQueues();
                
                echo json_encode( $queues );

                break;

            // case "addUserQueuePosts":
            case "addUserQueuePosts":

                $payload = array_to_xml( $_POST['data'] );

                $request = simplexml_load_string('<request>'.$payload.'</request>'); 

                $saved = $api->addUserQueuePosts( $request );

                echo json_encode( $saved );

                break;

            // case "setUserQueues":
            case "setUserQueues":

                $payload = array_to_xml( $_POST['data'] );

                $request = simplexml_load_string('<request>'.$payload.'</request>'); 

                $saved = $api->setUserQueues( $request );

                echo json_encode( $saved );

                break;

            // case "getAccountAutoPost":
            case "getAccountAutoPost":

                $payload = array_to_xml( $_POST['data'] );

                $request = simplexml_load_string('<request>'.$payload.'</request>'); 

                $saved = $api->getAccountAutoPost( $request );

                echo json_encode( $saved );

                break;
                
            // case "saveAccountAutoPost":
            case "saveAccountAutoPost":

                $payload = array_to_xml( $_POST['data'] );

                $request = simplexml_load_string('<request>'.$payload.'</request>'); 

                $saved = $api->saveAccountAutoPost( $request );

                echo json_encode( $saved );

                break;

            // case "getUserQueuePosts":
            case "getUserQueuePosts":

                $payload = array_to_xml( $_POST['data'] );

                $request = simplexml_load_string('<request>'.$payload.'</request>'); 

                $response = $api->getUserQueuePosts( $request );

                //echo json_encode( $response );die;
                $response = json_decode(json_encode( $response ), TRUE);

                $rows = array();
                if(isset($response['queues']['queue']))
                {
                    if( isset($response['queues']['queue'][0]) )
                        $rows = $response['queues']['queue'];
                    else
                        $rows[0] =  $response['queues']['queue'];
                }

                $posts = array();
                foreach ($rows as $row) 
                {
                    if(isset($row['posts']['post']))
                    {
                        if( isset($row['posts']['post'][0]) )
                        {
                            foreach ($row['posts']['post'] as $v) 
                            {
                                $v['queueId'] = $row['queueId'];

                                $posts[] = $v;
                            }
                            
                        }
                        else
                        {
                            $row['posts']['post']['queueId'] = $row['queueId'];
                            $posts[] = $row['posts']['post'];
                        }
                    }
                }
                
                $response['posts'] = $posts;

                //echo '<pre>';print_r($response);die;

                echo json_encode( $response );

                break;

            // case "deleteUserQueue":
            case "deleteUserQueue":

                $payload = array_to_xml( $_POST['data'] );

                $request = simplexml_load_string('<request>'.$payload.'</request>'); 

                $saved = $api->deleteUserQueue( $request );

                echo json_encode( $saved );

                break;
            
            // case "deleteUserQueuePosts":
            case "deleteUserQueuePosts":

                $payload = array_to_xml( $_POST['data'] );

                $request = simplexml_load_string('<request>'.$payload.'</request>'); 

                $saved = $api->deleteUserQueuePosts( $request );

                echo json_encode( $saved );

                break;

		endswitch;
	}
}

function getLastNotificationId( $username = null )
{
    $lastNotificationId = MultiCache::cache_get('lastNotificationId');

    if( !is_array($lastNotificationId) )
        $lastNotificationId = array();

    if( !is_null($username) )
    {
        if( isset($lastNotificationId[$username]) )
            return $lastNotificationId[$username];
        else
            return FALSE;
    }

    return $lastNotificationId;

}

function check_date_diff($d1 = null, $d2 = null, $diff = 7)
{
    $day = ($d1-$d2)/(60*60*24);

    return $day>$diff ? TRUE:FALSE;
}

function ajaxlog_write_close() {
    global $ajax_time_started, $ajax_label, $log_response_to_ec_error_handler;

    $bt = debug_backtrace();
    $ajax_time_ended = microtime(TRUE);
    $ajax_time_took  = sprintf('%.4f ms ',(($ajax_time_ended - $ajax_time_started) * 1000));
    ECErrorHandler::AJAXLog( trim($ajax_label) . ' took a total of '.$ajax_time_took, '', $bt);

    // IF we want to debug this...
    if ($log_response_to_ec_error_handler) {
        $rawdata = ob_get_flush();
        ECErrorHandler::AJAXLog( trim($ajax_label) . ' returned '.$rawdata . ' with headers ' . print_r(headers_list(), true), '', $bt);
    }
}

function ifNotArrayReturnArray($element) {
    if (is_array($element))
        return $element;
     if (empty($element))
         return array();
     return array($element);
}

function areIAndJCorrupt() {
    global $api;
    $debug = false;
    if ($debug) ECErrorHandler::error(__FUNCTION__ . ' started' . print_r($_POST, true));
    // If we have I and J set in the Post
    if (isset($_POST['i']) && isset($_POST['j']) && is_numeric($_POST['i']) && is_numeric($_POST['j'])) {
        $i = intval($_POST['i']);
        $j = intval($_POST['j']);
        if ($debug) ECErrorHandler::error(__FUNCTION__ . " have i $i and j $j");
        // If we have our cookie set
        if (!empty($_COOKIE['ACCOUNT_SAMPLE_ID'])) {
            if ($debug) ECErrorHandler::error(__FUNCTION__ . ' received account sample cookie ' . $_COOKIE['ACCOUNT_SAMPLE_ID']);
            // URL Decode cookie
            $_COOKIE['ACCOUNT_SAMPLE_ID'] = rawurldecode($_COOKIE['ACCOUNT_SAMPLE_ID']);
            // Explode cookie
            $exploded = explode(':', $_COOKIE['ACCOUNT_SAMPLE_ID']);
            if (count($exploded) == 2) {
                if ($debug) ECErrorHandler::error(__FUNCTION__ . ' got two items, checking... ' . print_r($exploded, true));
                
                $accounts = new SimpleXMLElement($api->getAccountsData('accounts'));
                // ECErrorHandler::error('HERE FALREY IS ACCOUNTS ' . print_r($accounts, true));
                $accounts = $accounts->analyticsAccounts->account;
                
                // ECErrorHandler::error('HERE FALREY IS ACCOUNTS ' . print_r($accounts[$i], true));
                
                $ajax_label = 'POST:action:' . $_POST ['action'] . ' by ' . (!empty($_SESSION['user_name']) ? $_SESSION['user_name'] : 'not logged in');
                
                if ($i < 0 || $i >= count($accounts)) {
                    ECErrorHandler::AJAXLog(__FUNCTION__ . " invalid account id $i, does not match an account in the existing accounts array :" . count($accounts) . " for request ".$ajax_label);
                    ECErrorHandler::error(__FUNCTION__ . " invalid account id $i, does not match an account in the existing accounts array: " . count($accounts) . " for request ".$ajax_label);
                    return true;
                }
                
                // ECErrorHandler::error('HERE FALREY IS ACCOUNT ' . print_r($accounts[$i], true));
                
                $config = $accounts[$i]->config;
                
                // ECErrorHandler::error('HERE FALREY IS CONFIG ' . print_r($config[$j], true));
                
                if ($j < 0 || $j >= count($config)) {
                    ECErrorHandler::AJAXLog(__FUNCTION__ . " invalid stream id $j, does not match an stream in the existing accounts array under account $i" . " for request ".$ajax_label);
                    ECErrorHandler::error(__FUNCTION__ . " invalid stream id $j, does not match an stream in the existing accounts array under account $i" . " for request ".$ajax_label);
                    return true;
                }
                
                // ECErrorHandler::error('HERE FALREY IS ACCOUNT ID ' . print_r($accounts[$i]->accountId, true));
                
                if ($accounts[$i]->accountId != $exploded[0]) {
                    ECErrorHandler::AJAXLog(__FUNCTION__ . " invalid account id $i, does not match cookie account id " . $exploded[0] . " for request ".$ajax_label);
                    ECErrorHandler::error(__FUNCTION__ . " invalid account id $i, does not match cookie account id " . $exploded[0] . " for request ".$ajax_label);
                    return true;
                }

                // ECErrorHandler::error('HERE FALREY IS SAMPLE ID ' . print_r($config[$j]->sampleId, true));

                if ($config[$j]->sampleId != $exploded[1]) {
                    ECErrorHandler::AJAXLog(__FUNCTION__ . " invalid sample id $j, does not match cookie account id " . $exploded[1] . " for request ".$ajax_label);
                    ECErrorHandler::error(__FUNCTION__ . " invalid sample id $j, does not match cookie account id " . $exploded[1] . " for request ".$ajax_label);
                    return true;
                }
            }
        }
    }
    if ($debug) ECErrorHandler::error(__FUNCTION__ . ' ended and is not corrupt');
    return false;
}

function array_to_xml( $array, $level = 1, $base_key = 'entry') 
{
    // if ( $level == 1 )  echo print_r( $array, false );

    $xml = '';
   // if ($level==1) {$xml .= "<array>\n";}

    foreach ( $array as $key => $value ) 
    {
        // $key = strtolower( $key );

        // echo "$level :: $key :: $value \n";

        if ( is_numeric( $key ) && $level == 1 ) $key = $base_key;

        // else if ( is_numeric( $key ) ) continue;

        if ( is_object( $value ) ) 
            { $value = get_object_vars( $value ); } // convert object to array
        
        if ( is_array( $value ) ) 
        {
            // if ( $level % 2 != 0 ) $xml .= "2=><$key2>XXX[$level]: \n";
            // else 
                

                // if ( ! is_numeric( $key ) ) $xml .= str_repeat("\t", $level )."<$key>\n";
                if ( ! is_numeric( $key ) ) $xml .= "<$key>";


            // $xml .= "<$key>\n";
            // $xml .= str_repeat("\t", $level )."2=><$key2>YYY[$level]: \n";
            // $xml .= str_repeat("\t", $level )."<$key2>\n";
            $xml .= array_to_xml( $value, $level + 1 );
            // $xml .= str_repeat("\t", $level )."</$key2>\n";
            // $xml .= str_repeat("\t", $level )."</$key2>\n";
            // if ( $level % 2 != 0 ) $xml .= "</$key2>\n";
            // else 
                

                // if ( ! is_numeric( $key ) ) $xml .= str_repeat("\t", $level )."</$key>\n"; 
                if ( ! is_numeric( $key ) ) $xml .= "</$key>"; 



            // foreach( $value as $key2 => $value2 ) 
            // {
            //     echo "$level :: $key2 :: $value2 - \n";

            //     // if ( is_numeric( $key2 ) ) $key2 = $base_key;

            //     if ( is_object( $value2 ) ) 
            //         { $value2 = get_object_vars( $value2 ); } // convert object to array

            //     if ( is_array( $value2 ) ) 
            //     {
            //         // if odd use YYY 
            //         if ( $level % 2 != 0 ) $xml .= "2=><$key2>XXX[$level]: \n";
            //         else $xml .= "1=><$key>XXX[$level]: \n";
            //         // $xml .= "<$key>\n";
            //         $xml .= str_repeat("\t", $level )."2=><$key2>YYY[$level]: \n";
            //         // $xml .= str_repeat("\t", $level )."<$key2>\n";
            //         $xml .= array_to_xml( $value2, $level + 1 );
            //         $xml .= str_repeat("\t", $level )."</$key2>\n";
            //         // $xml .= str_repeat("\t", $level )."</$key2>\n";
            //         if ( $level % 2 != 0 ) $xml .= "</$key2>\n";
            //         else $xml .= "</$key>\n"; 
            //         // $xml .= "</$key>\n";

            //         $multi_tags = true;
            //     } 

            //     else 
            //     {  
            //         if ( trim( $value2 ) != '') 
            //         {
            //             if ( htmlspecialchars( $value2 ) != $value2 ) 
            //             {
            //                 // $xml .= str_repeat("\t", $level )."<$key>ZZZ[$level]: \n";
            //                 // $xml .= str_repeat("\t", $level )."<$key>\n";
            //                 $xml .= str_repeat("\t", $level + 1 ).
            //                     "<$key2><![CDATA[$value2]]>". // changed $key to $key2... didn't work otherwise.
            //                     "</$key2>\n";

            //                 // $xml .= str_repeat("\t", $level ).":[$level]ZZZ</$key>\n";
            //                 // $xml .= str_repeat("\t", $level )."</$key>\n";
            //             } 

            //             else 
            //             {
            //                 // $xml .= str_repeat("\t", $level )."<$key>ZZZ[$level]: \n";
            //                 // $xml .= str_repeat("\t", $level )."<$key>\n";
            //                 $xml .= str_repeat("\t", $level + 1 ).
            //                     "2=><$key2>000[$level]:$value2</$key2>\n"; // changed $key to $key2

            //                 // $xml .= str_repeat("\t", $level ).":[$level]ZZZ</$key>\n";
            //                 // $xml .= str_repeat("\t", $level )."</$key>\n";
            //             }
            //         }
                    
            //         $multi_tags = true;
            //     }
            // }

            // if ( ! $multi_tags and count( $value ) > 0 ) 
            // {
            //     $xml .= str_repeat("\t", $level )."1=><$key>MULTI[$level]:\n";
            //     $xml .= array_to_xml( $value, $level + 1 );
            //     $xml .= str_repeat("\t", $level )."</$key>\n";
            // }
      
        }

        else 
        {
            if ( trim( $value ) != '') 
            {
                // echo ":: $key :: $value - \n";

                if ( htmlspecialchars( $value ) != $value ) 
                {
                    // $xml .= str_repeat("\t", $level )."<$key><![CDATA[$value]]></$key>\n";
                    $xml .= "<$key><![CDATA[$value]]></$key>";
                } 

                else 
                {
                    // $xml .= str_repeat("\t", $level )."<$key>$value</$key>\n";
                    $xml .= "<$key>$value</$key>";
                }
            }
        }
    }
   //if ($level==1) {$xml .= "</array>\n";}

    return $xml;
}

function isXML($xml){
   libxml_use_internal_errors(true);

   $doc = new DOMDocument('1.0', 'utf-8');
   $doc->loadXML($xml);

   $errors = libxml_get_errors();

   if(empty($errors)){
       return true;
   }

   $error = $errors[0];
   if($error->level < 3){
       return true;
   }

   $explodedxml = explode("r", $xml);
   $badxml = $explodedxml[($error->line)-1];

   $message = $error->message . ' at line ' . $error->line . '. Bad XML: ' . htmlentities($badxml);
   return $message;
}

//to check errors
function ajax_erro_shut(){

    $error = error_get_last();

    if($error ){
        ajax_error_handler($error['type'], $error['message'], $error['file'], $error['line']);
      }

}

function ajax_error_handler( $errno, $errstr, $errfile, $errline ) {

   echo  $errors = "ERROR NO:".$errno."</br>"."ERROR:".$errstr."</br>"."FILE:".$errfile."</br>"."ERROR LINE:".$errline;
}
