<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
		
/*
		  $config['email']['protocol'] = 'smtp';
		  $config['email']['smtp_host'] = 'ssl://smtp.gmail.com';
		  $config['email']['smtp_port'] = 465;
		  $config['email']['smtp_user'] = '';
		  $config['email']['smtp_pass'] = '';
		  $config['email']['mailtype'] = 'html';
 */
	
  $config['email']['protocol'] = 'sendmail';
  $config['email']['mailtype'] = 'html'; 



$config['email']['details'] = array(
							'email_id' 	=> 'info@roadshare.com',
							'from_name' => 'Road Sharing',
							'site_name' => 'roadsharing.com',
							'info_email_id' 	=> 'info@roadshare.com',
							'fax' => '123-456-789',
							'phone' => '123-456-789',
							'logo' => 'logo.jpg'
						);
	

/* End of file email.php */
/* Location: ./system/application/config/email.php */
