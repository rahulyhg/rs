<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

safe_include("controllers/admin/admin_controller.php");
class User extends Admin_controller {
    
    function __construct() 
    {
        parent::__construct();
        
    }


    function index()
    {

        $this->layout->add_javascripts(array('listing', 'rwd-table'));  

        $this->load->library('listing');

        
        $this->simple_search_fields = array(
                                                'user_name' => 'User'
        );
         
        $this->_narrow_search_conditions = array("start_date", "end_date", "customer", "order_status", "sales_channel", "type","followup","fraudulent","next_due_start_date","next_due_end_date","paid_status","overdue","ship_start_date","ship_end_date","orders_at_risk");
        
        $str = '<a href="'.site_url('admin/user/edit/{id}').'" class="table-link">
                    <span class="fa-stack">
                        <i class="fa fa-square fa-stack-2x"></i>
                        <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                    </span>
                </a>';
 
        $this->listing->initialize(array('listing_action' => $str));

        $listing = $this->listing->get_listings('user_model', 'listing');

        if($this->input->is_ajax_request())
            $this->_ajax_output(array('listing' => $listing), TRUE);
        
        $this->data['bulk_actions'] = array('' => 'select', 'print' => 'Print');
        $this->data['simple_search_fields'] = $this->simple_search_fields;
        $this->data['search_conditions'] = $this->session->userdata($this->namespace.'_search_conditions');
        $this->data['per_page'] = $this->listing->_get_per_page();
        $this->data['per_page_options'] = array_combine($this->listing->_get_per_page_options(), $this->listing->_get_per_page_options());
        
        $this->data['search_bar'] = $this->load->view('admin/listing/search_bar', $this->data, TRUE);        
        
        $this->data['listing'] = $listing;
        
        $this->data['grid'] = $this->load->view('admin/listing/view', $this->data, TRUE);
        
        $this->data['user_data'] = $this->session->userdata('admin_user_data');
        
        $this->layout->view("admin/user/test");
        
    }
    
	
    public function add($edit_id = 0)
    {
        
        
        $form = $this->input->post();
        $form = $this->security->xss_clean($form);

        if(isset($form['edit_id']))
            $edit_id = $form['edit_id'];


        $this->form_validation->set_rules($this->_validation_rules($edit_id));

        if($this->form_validation->run()) {
            
            $this->ins_data['first_name']         =   $form['first_name'];
            $this->ins_data['last_name']          =   $form['last_name'];
            $this->ins_data['about']              =   $form['about'];
            $this->ins_data['profile_name']       =   $form['profile_name'];
            $this->ins_data['user_name']          =   $form['user_name'];
            $this->ins_data['location']           =   $form['location'];
            $this->ins_data['email']              =   $form['email'];
            $this->ins_data['phone']              =   $form['phone'];
            $this->ins_data['gender']             =   $form['gender'];
            $this->ins_data['updated_time']       =   date("Y-m-d H:i:s");
            $this->ins_data['dob']                =   $form['dob'];
            $this->ins_data['updated_id']         =   get_current_user_id();
            $this->ins_data['created_id']         =   get_current_user_id();
            $this->ins_data['role']               =   $form['role'];
            
            if(trim($form['password']))
                $this->ins_data['password'] = md5($form['password']);
            
            if($edit_id) 
            {
                $this->user_model->update(array('id' => $edit_id), $this->ins_data);
                $this->service_message->set_flash_message("record_update_success");  

                //log
                actionLogAdd('user', $edit_id, "User#$edit_id ({$form['user_name']}) record has been updated.");
            }
            else
            {
                $user_id = $this->user_model->insert($this->ins_data);
                $this->service_message->set_flash_message("record_insert_success");

                //log
                actionLogAdd('user', $user_id, "User#$user_id ({$form['user_name']}) record has been created.");
            }
            
             redirect("admin/user");
        }
        
        if($edit_id) 
        {
            $edit_data = $this->user_model->get_where(array('id' => $edit_id))->row_array();
            if(!$edit_data) 
            {
                $this->service_message->set_flash_message("record_not_found_error");
                redirect("admin/user");  
            }
            //unset password
            $edit_data['password'] = '';
            $this->data['form_data'] = $edit_data;
        }
        else if($form) 
        {
            $this->data['form_data'] = $form;
            $this->data['form_data']['id'] = $edit_id ?$edit_id:0;
        }
        else
        {
            $this->data['form_data']=array("id"=>'','first_name'=>'',"last_name"=>'',"email"=>'',"phone" => '',"profile_name" => '', "role" => '','user_name' => '', 'password' => '', 'about' => '', 'location' => '','dob' => '','gender' => '');
        }
                
        //Get roles
        $this->data['roles']     = get_roles();
        
        $this->layout->view("admin/user/add");
    }
    
   public function _validation_rules( $edit_id = 0)
   {
    
    $rules = array();
    $rules[] = array('field' => 'first_name', 'label' => 'Firstname', 'rules' => 'trim|required|alpha|max_length[255]');
    $rules[] = array('field' => 'last_name', 'label' => 'Lastname', 'rules' => 'trim|required|alpha|max_length[255]');
    $rules[] = array('field' => 'about', 'label' => 'About', 'rules' => 'trim|required');
    $rules[] = array('field' => 'profile_name', 'label' => 'Profilename', 'rules' => 'trim|required|alpha_numeric');
    $rules[] = array('field' => 'role', 'label' => 'Role', 'rules' => 'trim|required');
    $rules[] = array('field' => 'dob', 'label' => 'DOB', 'rules' => 'trim');
    $rules[] = array('field' => 'location', 'label' => 'Location', 'rules' => 'trim|required');
    $rules[] = array('field' => 'phone', 'label' => 'Phone', 'rules' => 'trim|required|numeric|min_length[10]');
    $rules[] = array('field' => 'gender', 'label' => 'Gender', 'rules' => 'trim|required');

    if($edit_id)
    {
        $rules[] = array('field' => 'email', 'label' => 'Email', 'rules' => 'trim|required|valid_email');
        $rules[] = array('field' => 'user_name', 'label' => 'Username', 'rules' => 'trim|required|alpha');
        $rules[] = array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|min_length[8]|max_length[20]');
    }
    else
    {
        $rules[] = array('field' => 'email', 'label' => 'Email', 'rules' => 'trim|required|valid_email|callback_unique_email_check');
        $rules[] = array('field' => 'user_name', 'label' => 'Username', 'rules' => 'trim|required|alpha|callback_unique_username_check');
        $rules[] = array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|required|min_length[8]|max_length[20]');
    }
    
    return $rules;
   } 
   
   public function unique_email_check($email)
   {
     if($email) {
         $userdata = $this->user_model->get_by_email($email);
         if(count($userdata)) {
            $this->form_validation->set_message('unique_email_check', 'Email Already Exists');
            return false;
         }
         return true;
     }
    
   }
   public function unique_username_check($username)
   {
     if($username) {
        $userdata = $this->user_model->get_by_loginid($username);
        if(count($userdata)) {
            $this->form_validation->set_message('unique_username_check', 'Username Already Exists');
            return false;
        }
        return true;
     }
   }

   function ttt()
   {
     actionLogAdd('so', 12, 'hhhhh hhh');
   }
}

/* End of file user.php */
/* Location: ./application/controllers/User.php */