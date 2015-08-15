<?php if(!defined("BASEPATH")) exit("No direct script access allowed");

safe_include("controllers/app_controller.php");
class Join_now extends App_Controller {
    function __construct()
    {
        parent::__construct();       
        
        $this->load->model('user_model');
    }

    public function index()
    {
        $this->form_validation->set_rules($this->get_rules());

        if($this->form_validation->run())
        {
            $form = $this->input->post();
            
            $insert = array();
            $insert['first_name']   = $form['first_name'];
            $insert['last_name']    = $form['last_name'];
            $insert['email']        = $form['email'];
            $insert['password']     = md5($form['password']);
            $insert['dob']          = $form['dob'];
            $insert['role']         = 2;
            $insert['news_letter']  = isset($form['agree'])?1:0;

            $this->user_model->insert($insert);

            $this->service_message->set_flash_message('signup_success', $insert['first_name']);

            redirect();
        }

        $this->layout->view('frontend/join_now/join_now');
    }

    function get_rules()
    {
        $rules = array(
                    array('field' => 'first_name', 'label' => 'First Name', 'rules' => 'trim|required|max_length[255]'),
                    array('field' => 'last_name', 'label' => 'Last Name', 'rules' => 'trim|required|max_length[255]'),
                    array('field' => 'email', 'label' => 'Email Address', 'rules' => 'trim|required|xss_clean'),
                    array('field' => 'dob', 'label' => 'Date of Birth', 'rules' => 'trim|required'),
                    array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|required|xss_clean|min_length[4]|max_length[20]|alpha_dash'),
                    array('field' => 'confirm_password', 'label' => 'Confirm Password', 'rules' => 'trim|required|xss_clean|min_length[4]|max_length[20]|alpha_dash | matches[Password]'),
                    array('field' => 'agree', 'label' => 'agree', 'rules' => 'trim'),
                );

        return $rules;
    }
    
    
	
}
?>
