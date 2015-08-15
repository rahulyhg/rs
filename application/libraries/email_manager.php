<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email_manager
{
	private $_CI;
	private $_cc = array();
	private $_bcc = array();

	public function __construct($options = array())
	{
		$this->_CI = & get_instance();
		$this->_CI->error_message = '';
		
		foreach ($options as $key => $value) 
		{
			$key = "_{$key}";
			if (isset($this->$key))
				$this->$key = $value;	
		}
		
	}
	
	public function initialize($params = array())
	{
		if(!count($params))
			return FALSE;
	
		foreach ($params as $key => $val)
		{
			$key = "_{$key}";
			if (isset($this->$key))
				$this->$key = $val;
		}
	
	}
	
	public function send_email($to, $toname, $from, $from_name, $subject, $message, $cc = array(),$attachments = array())
	{
		$this->_CI->config->load('email');
	
		$this->_CI->load->library('email', $this->_CI->config->item('email'));

		$this->_CI->email->clear(TRUE);
		
		$this->_CI->email->set_newline("\r\n");
	
		$this->_CI->email->from($from,$from_name);
		$this->_CI->email->to($to);
		$this->_CI->email->cc( array_merge($cc, $this->_cc) );
		$this->_CI->email->bcc($this->_bcc);

		$this->_CI->email->subject($subject);
		$this->_CI->email->message($message);
		foreach ($attachments as $file)
			$this->_CI->email->attach($file);
		
		if ( ! $this->_CI->email->Send())
			return FALSE;
		
		return TRUE;
	}
	
	
	function send_sign_up_confirmation($data = array())
	{
		//send email to user.
		$data['name'] 		= "{$data['first_name']} {$data['first_name']}";
		$data['email'] 		= $data['email'];
		$data['password'] 	= $data['passwd'];
		
		$data['message'] = $this->_CI->load->view('email/'.$this->_CI->sales_channel_id.'/welcome-html', $data, TRUE);
		
		$message = $this->_CI->load->view('email/'.$this->_CI->sales_channel_id.'/system_email_template', $data, TRUE);
		
		$this->load->helper('email_config');
		$email_details = get_settings($this->_CI->sales_channel_id, 'general');
		if($email_details)
		{
			if(!$this->send_email($data['email'], $data['name'], $email_details['email_id'], $email_details['from_name'], "Welcome to {$email_details['site_name']}", $message))
			{
				$this->_CI->error_message = "Email sending is failed.";
				return FALSE;
			}
				
		}
			
		return TRUE;
	}
	
	

}