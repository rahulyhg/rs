<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Controller extends CI_Controller
{
    protected $_logged_in_only         =    false;
    public $error_message              =    '';
    public $data                       =    array();
    public $role                       =    '';
    public $load_css                   =    array();
    public $load_js                    =    array();
    public $ins_data                   =    array();
    
    protected $_login_validation_rules =    array (
                                                    array('field' => 'email', 'label' => 'Email', 'rules' => 'trim|required|xss_clean'),
                                                    array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|required|xss_clean|min_length[4]|max_length[20]|alpha_dash')
                                                  );
   protected $useradd_validation_rules =    array();  
   protected $role_validation_rules    =    array();
   
    
    public function __construct()
    {
        parent::__construct(); 

        if(!is_logged_in()) {
            
            redirect('login');
        }    
       
        $this->data = array();
        $this->role = get_user_role();
        
        $this->load->library("form_validation");
        
        $this->load->model("user_model");
        $this->load->model("role_model");
        
    }
    
    public function index()
    {
       
    }
    
    public function _ajax_output($data, $json_format = FALSE)
    {
    	if(is_array($data) && $json_format)
        	echo json_encode($data);
    	else 
    		echo $data;
    	
        exit();
    }
    
    
  
}

?>
