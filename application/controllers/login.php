<?php if(!defined("BASEPATH")) exit("No direct script access allowed");

safe_include("controllers/app_controller.php");
class Login extends App_Controller {
	
	
	protected $_login_validation_rules =    array (
                                                    array('field' => 'email', 'label' => 'Email', 'rules' => 'trim|required|xss_clean'),
                                                    array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|required|xss_clean|min_length[4]|max_length[20]|alpha_dash')
                                                  );
                                                  
   protected $_forgot_password_validation_rules = array(
													array('field' => 'email',  'label' => 'Email', 'rules' => 'trim|required|xss_clean|valid_email')
													);
                                                  
    private $_reset_password_validation_rules = array(
											        array('field' => 'password',  'label' => 'Password', 'rules' => 'trim|required|xss_clean|min_length[4]|max_length[20]'),
											        array('field' => 'retype_password',  'label' => 'Retype Password', 'rules' => 'trim|required|xss_clean|matches[password]')
    );
    
    protected $_signup_validation_rules = array(
													array('field' => 'first_name', 'label' => 'Firstname', 'rules' => 'trim|required|alpha|max_length[255]'),
													array('field' => 'last_name', 'label' => 'Lastname', 'rules' => 'trim|required|alpha|max_length[255]'),
													array('field' => 'email', 'label' => 'Email', 'rules' => 'trim|required|xss_clean'),
													array('field' => 'user_name', 'label' => 'Username', 'rules' => 'trim|required|alpha_numeric'),
													array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|required|xss_clean|min_length[4]|max_length[20]|alpha_dash'),
													array('field' => 'confirm_password', 'label' => 'Confirm Password', 'rules' => 'trim|required|xss_clean|min_length[4]|max_length[20]|alpha_dash | matches[Password]')
													);
													
	protected $_settings_validation_rules = array(
													array('field' => 'first_name', 'label' => 'Firstname', 'rules' => 'trim|required|alpha|max_length[255]'),
													array('field' => 'last_name', 'label' => 'Lastname', 'rules' => 'trim|required|alpha|max_length[255]'),
													array('field' => 'email', 'label' => 'Email', 'rules' => 'trim|required|xss_clean'),
													array('field' => 'user_name', 'label' => 'Username', 'rules' => 'trim|required|alpha_numeric'),
													array('field' => 'profile_name', 'label' => 'Profile name', 'rules' => 'trim|required|alpha_numeric'),
													array('field' => 'phone', 'label' => 'Phone', 'rules' => 'trim|required|numeric|min_length[10]'),
													array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|required|xss_clean|min_length[4]|max_length[20]|alpha_dash'),
													array('field' => 'about', 'label' => 'About', 'rules' => 'trim|required|max_length[255]'),
													array('field' => 'location', 'label' => 'Location', 'rules' => 'trim|required|alpha|max_length[255]')
													
													);
                                                  
    
    
    function __construct()
    {
        parent::__construct();
        
        //$this->layout->add_javascripts(array('custom'));
        //$this->load->library('form_validation');
        //$this->load->model('userlogin_model');
        //$this->load->library('upload_manager');

         
    }

    public function index()
    {
        $this->form_validation->set_rules($this->_login_validation_rules);
       
        if($this->form_validation->run())
        {
            $form = $this->input->post();
           

            $user_data = $this->user_model->get_by_email($form['email']);
            //print_r($user_data);exit;
            
            if( isset($user_data['password']) && strcmp( $user_data['password'], md5($form['password']) ) === 0 )
            {
                $this->session->set_userdata('user_data', $user_data);
            	$this->service_message->set_flash_message("login_success");
                redirect('dashboard');
            }
            else
            {
				$this->service_message->set_message("login_error");
			}
            
        }

        $this->layout->view('frontend/login/login');
    }
    
	
	public function forgot_password()
	{
		
		$this->form_validation->set_rules($this->_forgot_password_validation_rules);

        if ($this->form_validation->run()) 
        { 
            $email = $this->input->post('email');
            //get user details by given email-id.
            $user = $this->user_model->get_by_email($email);

            if (count($user)) 
            {
                $str = 'Your Request has been processed.Please click the following link to reset password. ';

                $str .= '<a href = "'.base_url().'reset_password/'.$this->get_encoded_string($email).'">Click here</a>';

                $this->load->library('email_manager');
                $this->load->config('email');
                $email_details = $this->config->item('details','email');
                die($str);
                if( $email_details )
                    $this->email_manager->send_email($email, '', $email_details['email_id'], $email_details['from_name'], "{$email_details['site_name']} - Password Reset Link", $str);  
            
                $this->service_message->set_flash_message('password_restore_success');

                redirect('login');
            }

            $this->service_message->set_message('password_restore_error');
        }
		
		
		$this->layout->view('frontend/login/forgot_password');
	}
	
	function reset_password()
    {
		$enc_str =  str_replace('/login/reset_password/',"",$_SERVER['PATH_INFO']);
        
        $this->load->library('encrypt');
        
        //$enc_str    = $this->input->post('enc_str');
        $email   = $this->encrypt->decode($enc_str);
        
        $this->data['enc_str'] = $enc_str;
        
        
        //check if email-id is valid
        $result = $this->user_model->get_by_email($email);
        if( count($result) )
        {
            $user_details = $result;
            $this->form_validation->set_rules($this->_reset_password_validation_rules);
            if ($this->form_validation->run()) 
            { 
                $password = $this->input->post('password');
                $password = md5($password);
                
                //update user record with new password.
                $this->user_model->update(array('id' => $user_details['id']), array('password' => $password));

                $this->service_message->set_flash_message('password_reset_success');
                redirect('login');
            }

            $this->layout->view('frontend/login/reset_password');
        }
        else
        {
            $this->layout->view('frontend/login/invalid_email_password', $data);
        }
    }

	private function get_encoded_string($str = null)
    {
        $this->load->library('encrypt');
        return $this->encrypt->encode($str);
    }

    private function get_decoded_string($str = null)
    {
        $this->load->library('encrypt');
        return $this->encrypt->decode($str);
    }
	
	public function logout()
	{
	   
		$this->session->sess_destroy();
	
		$this->session->sess_create();
		$this->service_message->set_flash_message('logout_success');
	
		redirect();
	}
	
	
	
	
	
	

	
	
	
	
}
?>
