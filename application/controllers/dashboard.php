<?php if(!defined("BASEPATH")) exit("No direct script access allowed");

safe_include("controllers/app_controller.php");
class Dashboard extends App_Controller {
    public $user_id;
    function __construct()
    {
        parent::__construct();       
        $this->user_id = get_current_user_id();
        $this->load->helper('car');
        $this->load->model('car_model');
    }

    public function index()
    {
    	
        $this->data['tmenu'] = $this->load->view('frontend/_partials/top-menu', $this->data, TRUE);
        $this->data['smenu'] = $this->load->view('frontend/_partials/profile-left-menu', $this->data, TRUE);
        
        $this->layout->view('frontend/dashboard/dashboard');

    }

    public function profile( $type = 'general' )
    {

        $this->data['type']  = $type;
        $this->data['tmenu'] = $this->load->view('frontend/_partials/top-menu', $this->data, TRUE);
        $this->data['smenu'] = $this->load->view('frontend/_partials/profile-left-menu', $this->data, TRUE);
        

        switch ($type) {

            case 'general':

                $this->form_validation->set_rules($this->get_rules($type));

                $this->data['general'] = $this->user_model->get_where(array('id' => $this->user_id))->row_array();

                if($this->form_validation->run())
                {
                    $form = $this->input->post();
                    $insert = array();
                    $insert['gender']       = $form['gender'];
                    $insert['first_name']   = $form['first_name'];
                    $insert['last_name']    = $form['last_name'];
                    $insert['email']        = $form['email'];
                    $insert['phone']        = $form['phone'];
                    $insert['dob']          = $form['dob'];
                    $insert['bio']          = $form['bio'];

                    $this->user_model->update(array('id' => $this->user_id), $insert);

                    $this->service_message->set_message('custom_message_success', 'Updated successfully.');
                }

                break;
            case 'picture':
                $this->layout->add_javascripts(array('SimpleAjaxUploader'));
                $this->init_scripts = array('frontend/dashboard/ajax_uploader_script');               
                
                $this->data['general'] = $this->user_model->get_where(array('id' => $this->user_id))->row_array();
                

                break;


            case 'address':

                $this->data['general'] = $this->user_model->get_where(array('id' => $this->user_id))->row_array();

                $address = array();
                $address['user_id']     = $this->user_id;
                $address['first_name']  = $this->data['general']['first_name'];
                $address['last_name']   = $this->data['general']['last_name'];
                $address['address_1']   = '';
                $address['address_2']   = '';
                $address['zip']         = '';
                $address['city']        = '';
                $address['country']     = 'HK';

                $result = $this->user_model->get_address_by_user_id($this->user_id);

                if( count($result) )
                    $address = $result;

                $this->data['address'] = $address;

                $this->form_validation->set_rules($this->get_rules($type));

                if($this->form_validation->run())
                {
                    $form = $this->input->post();
                    $insert = array();
                    $insert['first_name']   = $form['first_name'];
                    $insert['last_name']    = $form['last_name'];
                    $insert['address_1']    = $form['address_1'];                    
                    $insert['address_2']    = $form['address_2'];
                    $insert['zip']          = $form['zip'];
                    $insert['city']         = $form['city'];
                    $insert['country']      = $form['country'];
                    $insert['user_id']      = $this->user_id;

                    if( isset($address['id']) && $address['id'] )
                    {
                        $this->user_model->update(array('user_id' => $this->user_id), $insert, 'address');
                    }
                    else
                    {
                        $this->user_model->insert($insert, 'address');   
                    }
                    

                    $this->service_message->set_message('custom_message_success', 'Updated successfully.');

                   

                }

                break;


            case 'preferences':

                $form = $this->input->post();
                $insert = array();
                $insert['user_id']      = $this->user_id;
                $insert['chattiness']   = $form['chattiness'];
                $insert['smoking']      = $form['smoking'];
                $insert['pets']         = $form['pets'];                    
                $insert['music']        = $form['music'];

                $result = $this->user_model->get_where(array('user_id' => $this->user_id), '*', 'preferences')->row_array();
                if( is_array($result) && $result  )
                {
                    $this->user_model->update(array('user_id' => $this->user_id), $insert, 'preferences');
                }
                else
                {
                    $this->user_model->insert($insert, 'preferences');   
                }
                

                $this->service_message->set_message('custom_message_success', 'Updated successfully.');

                   

                

                break;

            case 'car':
                $this->layout->add_javascripts(array('SimpleAjaxUploader'));
                $action = $this->uri->segment(4, 'list');

                switch ( $action ) 
                {
                    case 'list':  
                        $type = 'car_list';                      
                        $cars = $this->car_model->get_cars();
                        $this->data['cars'] = $cars;
                        break;
                    
                    case 'add':
                    case 'edit':
                        $type = 'car_add';
                        $car = array();
                        $car['make_id']     = '1';
                        $car['model_id']    = '';
                        $car['comfort_id']  = '1';
                        $car['colour_id']   = '1';
                        $car['type_id']     = '1';
                        $car['seat_count']  = '';                        

                        $car_id = $this->uri->segment(5, 0);

                        if( $car_id )
                            $car = $this->car_model->get_where(array('id' => $car_id))->row_array();

                        $this->data['car'] = $car;

                        $this->form_validation->set_rules($this->get_rules($type));

                        if($this->form_validation->run())
                        {
                            $form = $this->input->post();
                            $insert = array();
                            $insert['make_id']      = $form['make_id'];
                            $insert['model_id']     = $form['model_id'];
                            $insert['comfort_id']   = $form['comfort_id'];                    
                            $insert['colour_id']    = $form['colour_id'];
                            $insert['type_id']      = $form['type_id'];
                            $insert['seat_count']   = $form['seat_count'];
                            $insert['user_id']      = $this->user_id;
                            
                            if( $car_id )
                                $this->car_model->update(array('id' => $car_id), $insert);
                            else
                                $this->car_model->insert($insert);
                            
                            $this->service_message->set_flash_message('custom_message_success', 'Updated successfully.');
                            redirect('dashboard/profile/car');
                        }
                        break;
                    case 'delete':  
                        $car_id = $this->uri->segment(5, 0);
                        $this->car_model->delete( array('id' => $car_id) );
                        //echo $this->db->last_query();die;
                        redirect('dashboard/profile/car');
                        break;
                    case 'get_models':
                        $make_id = $this->uri->segment(5, 0);
                        $models = get_models( $make_id, false );
                        $content = array();
                        foreach ($models as $key => $val) 
                        {
                            $content[] = array('id' => $key, 'value'=> $val);
                        }
                       
                        echo json_encode(array('content' => $content ));die;

                    default:
                        # code...
                        break;
                }

                

                break;
            
            case 'change_password':

                $this->data['general'] = $this->user_model->get_where(array('id' => $this->user_id))->row_array();

                $this->form_validation->set_rules($this->get_rules($type));

                if($this->form_validation->run())
                {
                    $form = $this->input->post();
                    $insert = array();
                    $insert['password']     = $form['password'];

                    $this->user_model->update(array('id' => $this->user_id), $insert);                    

                    $this->service_message->set_message('custom_message_success', 'Updated successfully.');

                    

                }

                break;
            default:
                # code...
                break;
        }

        $this->layout->view('frontend/dashboard/'.$type);
    }

    public function rides_offered()
    {
        
        $this->data['tmenu'] = $this->load->view('frontend/_partials/top-menu', $this->data, TRUE);
        
        $this->layout->view('frontend/dashboard/dashboard');

    }

    public function messages()
    {
        
        $this->data['tmenu'] = $this->load->view('frontend/_partials/top-menu', $this->data, TRUE);
        $this->data['smenu'] = $this->load->view('frontend/_partials/profile-left-menu', $this->data, TRUE);
        
        $this->layout->view('frontend/dashboard/dashboard');

    }

    public function emails()
    {
        
        $this->data['tmenu'] = $this->load->view('frontend/_partials/top-menu', $this->data, TRUE);
        $this->data['smenu'] = $this->load->view('frontend/_partials/profile-left-menu', $this->data, TRUE);
        
        $this->layout->view('frontend/dashboard/dashboard');

    }

    function upload_photo()
    {
        $this->load->library('FileUpload');
        $upload_dir = 'assets/uploads/users/';


        $uploader = new FileUpload('uploadfile');

        // Handle the upload
        $result = $uploader->handleUpload($upload_dir);
        

        if (!$result) {
          exit(json_encode(array('success' => false, 'msg' => $uploader->getErrorMsg())));  
        }

        $update = array('profile_img' => $uploader->getFileName());
        $this->user_model->update(array('id' => $this->user_id), $update);
        echo json_encode(array('success' => true, 'fname' => $uploader->getFileName()));

        exit;
    }

    function upload_car( $id = 0 )
    {
        $this->load->library('FileUpload');
        $upload_dir = 'assets/uploads/cars/';


        $uploader = new FileUpload('uploadfile');

        // Handle the upload
        $result = $uploader->handleUpload($upload_dir);
        

        if (!$result) {
          exit(json_encode(array('success' => false, 'msg' => $uploader->getErrorMsg())));  
        }

        $update = array('img_name' => $uploader->getFileName());
        $this->car_model->update(array('id' => $id), $update);
        echo json_encode(array('success' => true, 'fname' => $uploader->getFileName()));

        exit;
    }

    function upload_progress()
    {
        // This "if" statement is only necessary for CORS uploads -- if you're
        // only doing same-domain uploads then you can delete it if you want
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        if (isset($_REQUEST['progresskey'])) {
            $status = apc_fetch('upload_'.$_REQUEST['progresskey']);
        } else {
            exit(json_encode(array('success' => false)));
        }

        $pct = 0;
        $size = 0;

        if (is_array($status)) {

            if (array_key_exists('total', $status) && array_key_exists('current', $status)) {

                if ($status['total'] > 0) {
                    $pct = round(($status['current'] / $status['total']) * 100);
                    $size = round($status['total'] / 1024);
                }
            }
        }

        echo json_encode(array('success' => true, 'pct' => $pct, 'size' => $size));
    }

    function get_rules( $type = 'general' )
    {
        $rules = array();

        switch ( $type ) 
        {
            case 'general':
                $rules = array(
                    array('field' => 'gender', 'label' => 'Gender', 'rules' => 'trim|required'),
                    array('field' => 'first_name', 'label' => 'First Name', 'rules' => 'trim|required|max_length[255]'),
                    array('field' => 'last_name', 'label' => 'Last Name', 'rules' => 'trim|required|max_length[255]'),
                    array('field' => 'email', 'label' => 'Email Address', 'rules' => 'trim|required|valid_email'),
                    array('field' => 'dob', 'label' => 'Date of Birth', 'rules' => 'trim|required'),
                    array('field' => 'phone', 'label' => 'Mobile Phone', 'rules' => 'trim'),
                    array('field' => 'bio', 'label' => 'Bio', 'rules' => 'trim'),                    
                );
                break;

            case 'car_add':
                $rules = array(
                    array('field' => 'make_id', 'rules' => 'trim|required'),
                    array('field' => 'model_id', 'rules' => 'trim|required'),
                    array('field' => 'comfort_id', 'rules' => 'trim|required'),
                    array('field' => 'colour_id', 'rules' => 'trim|required'),
                    array('field' => 'type_id', 'rules' => 'trim|required'),
                    array('field' => 'seat_count', 'rules' => 'trim|required')     
                );
                break;

            case 'change_password':
                $rules = array(
                    array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|required|xss_clean|min_length[4]|max_length[20]'),
                    array('field' => 'retype_password', 'label' => 'Retype Password', 'rules' => 'trim|required|xss_clean|matches[password]')     
                );
                break;
            
            default:
                $rules = array(
                    array('field' => 'first_name', 'rules' => 'trim|required|max_length[255]'),
                    array('field' => 'last_name', 'rules' => 'trim|required|max_length[255]'),
                    array('field' => 'address_1', 'rules' => 'trim|required'),
                    array('field' => 'address_2', 'rules' => 'trim|required'),
                    array('field' => 'zip', 'rules' => 'trim|required'),
                    array('field' => 'city', 'rules' => 'trim|required'),  
                    array('field' => 'country', 'rules' => 'trim|required'),                   
                );
                break;
        }
        

        return $rules;
    }


    function offer_seats()
    {
        if( !is_logged_in() )
            redirect('login');

        $this->init_scripts = array('frontend/_partials/google_script'); 

        if( $this->input->is_ajax_request() )
        {
            $this->load->model('rides_model');

            try
            {
                $this->db->trans_begin();

                if( !get_current_user_id() )
                    throw new Exception("Your session is expired.");

                $form = $this->security->xss_clean($this->input->post());
                
                //Enter rides data
                $rides = array();
                $rides['user_id']       = get_current_user_id();
                $rides['origin_name']   = $form['origin_name'];
                $rides['origin_latlng'] = $form['origin_latlng'];
                $rides['origin_address']= $form['origin_address'];
                $rides['dest_name']     = $form['dest_name'];
                $rides['dest_latlng']   = $form['dest_latlng'];
                $rides['dest_address']  = $form['dest_address'];
                $rides['schedule_type'] = 'OT';
                $rides['ride_type']     = ($form['ride_type'] == 'up_down')?'R':'S';

                $ride_id = $this->rides_model->insert($rides);

                if( !$ride_id )
                    throw new Exception("Database error.");

                //Enter Ride Details
                $ride_details = [];
                $ride_details['ride_id']                = $ride_id;
                $ride_details['seat_count']             = $form['seat_count'];
                $ride_details['description']            = $form['ride_details'];
                $ride_details['luggage']                = $form['luggage'];
                $ride_details['schedule_flexibility']   = $form['schedule_flexibility'];
                $ride_details['detour_flexibility']     = $form['detour_flexibility'];
                $ride_details['total_dist']             = $form['total_dist'];
                $ride_details['total_time']             = $form['total_time'];

                $this->rides_model->insert($ride_details, 'ride_details');

                //Schedules
                $dep_date =  $this->input->post('dep_date');

                if( !is_valid_date($dep_date, 'Y-m-d H:i') )
                    throw new Exception("Deaprture date is invalid.");

                $ride_schedules = [];
                $ride_schedules['ride_id']               = $ride_id;
                $ride_schedules['ride_day']              = date('w', strtotime($dep_date));
                $ride_schedules['ride_start_time']       = date('H:i', strtotime($dep_date));
                $ride_schedules['schedule_start_date']   = date('Y-m-d', strtotime($dep_date));
                $ride_schedules['schedule_end_date']     = date('Y-m-d', strtotime($dep_date));
                $ride_schedules['towards']               = 'up';

                $this->rides_model->insert($ride_schedules, 'ride_schedules');

                if( $this->input->post('ride_type') == 'up_down' )
                {
                    $ret_date =  $this->input->post('ret_date');

                    if( !is_valid_date($ret_date, 'Y-m-d H:i') )
                        throw new Exception("Return date is invalid.");

                    $ride_schedules = [];
                    $ride_schedules['ride_id']               = $ride_id;
                    $ride_schedules['ride_day']              = date('w', strtotime($ret_date));
                    $ride_schedules['ride_start_time']       = date('H:i', strtotime($ret_date));
                    $ride_schedules['schedule_start_date']   = date('Y-m-d', strtotime($ret_date));
                    $ride_schedules['schedule_end_date']     = date('Y-m-d', strtotime($ret_date));
                    $ride_schedules['towards']               = 'down';

                    $this->rides_model->insert($ride_schedules, 'ride_schedules');
                }

                //Waypoints
                $waypoints =  $this->input->post('waypoints');

                if( isset($waypoints) && is_array($waypoints) )
                {
                    foreach ($waypoints as $waypoint) 
                    {
                        $temp = array();
                        $temp['ride_id']    = $ride_id;
                        $temp['wp_name']    = $waypoint['name'];
                        $temp['wp_address'] = $waypoint['address'];
                        $temp['wp_latlng']  = $waypoint['latlng'];

                        $this->rides_model->insert($temp, 'ride_waypoints');
                    }        
                }
                

                if ($this->db->trans_status() === FALSE)
                    throw new Exception("Database error.");
                    
                $this->db->trans_commit();

                $status     = 'success';
                $message    = 'Published successfully.';
            }
            catch(Exception $e)
            {
                $status = 'error';
                $message = $e->getMessage();
                $this->db->trans_rollback();
            }

            $output = array('status' => $status, 'message' => $message);
            $this->_ajax_output($output, TRUE);
        }

        $this->data['tmenu'] = $this->load->view('frontend/_partials/top-menu', $this->data, TRUE);
        $this->layout->view('frontend/home/offer_seats');
    }  
    
    
	
	
}
?>
