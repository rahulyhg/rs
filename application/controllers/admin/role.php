<?php if(!defined("BASEPATH")) exit('No direct script access allowed');
safe_include('controllers/admin/admin_controller.php');

class Role extends Admin_Controller {
    
    function __construct() {
        parent::__construct();
    }

    function index()
    {

        $this->layout->add_javascripts(array('listing', 'rwd-table'));  

        $this->load->library('listing');

        
        $this->simple_search_fields = array(
                                                'name' => 'Role'
        );
         
        $this->_narrow_search_conditions = array();
        
        $str = '<a href="'.site_url('admin/role/edit/{id}').'" class="table-link">
                    <span class="fa-stack">
                        <i class="fa fa-square fa-stack-2x"></i>
                        <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                    </span>
                </a>';
 
        $this->listing->initialize(array('listing_action' => $str));

        $listing = $this->listing->get_listings('role_model', 'listing');

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
        
        $this->layout->view("admin/role/list");
        
    }
    
    public function add($edit_id = 0)
    {
        
        
        $form = $this->input->post();
        $form = $this->security->xss_clean($form);

        if(isset($form['edit_id']))
            $edit_id = $form['edit_id'];


        $this->form_validation->set_rules($this->_validation_rules_message());

        if($this->form_validation->run()) {
            
            $this->ins_data['name']         =   $form['name'];
            $this->ins_data['updated_date']       =   date("Y-m-d H:i:s");
            $this->ins_data['updated_id']         =   get_current_user_id();
            $this->ins_data['created_id']         =   get_current_user_id();
            
            if($edit_id) 
            {
                $this->role_model->update(array('id' => $edit_id), $this->ins_data);
                $this->service_message->set_flash_message("record_update_success");  
                actionLogAdd('role', $edit_id, "Role#$edit_id ({$form['name']}) record has been updated.");
            }
            else
            {
                $role_id = $this->role_model->insert($this->ins_data);
                $this->service_message->set_flash_message("record_insert_success");

                actionLogAdd('role', $role_id, "Role#$role_id ({$form['name']}) record has been created.");
            }
            
             redirect("admin/role");
        }
        
        if($edit_id) 
        {
            $edit_data = $this->role_model->get_where(array('id' => $edit_id))->row_array();
            if(!$edit_data) 
            {
                $this->service_message->set_flash_message("record_not_found_error");
                redirect("admin/user");  
            }
            $this->data['form_data'] = $edit_data;
        }
        else if($form) 
        {
            $this->data['form_data'] = $form;
            $this->data['form_data']['id'] = $edit_id ?$edit_id:0;
        }
        else
        {
            $this->data['form_data']=array("id"=>'','name'=>'');
        }
                
        
        $this->layout->view("admin/role/add");
    }

    
	
	public function _validation_rules_message()
	{
		return $this->message_add_validation_rules = array(array('field' => 'name', 'label' => 'role', 'rules' => 'trim|required|xss_clean|max_length[255]'));
		
	}
	
    
    
    public function manage_role()
    {
		$this->load->model('admin/role_model');
        $this->result = $this->role_model->get_all_role();
		
		$this->data['css']       = get_css('user_add');
        $this->data['js']        = get_js('user_add'); 
		$this->layout->view("admin/role/manage_role",$this->result);
	}
	
	
	public function edit_role($id = "")
	{
		
		$this->form_validation->set_rules($this->_validation_rules_message());
		if($this->form_validation->run()) { 
            
            $this->ins_data['role']         =   $this->input->post('role');
            $this->ins_data['id']              =   $this->input->post('id');
			
			$this->load->model('admin/role_model');
            $this->role_model->edit_role($this->ins_data);
            $this->service_message->set_flash_message("record_insert_success");
            
			redirect("admin/role/manage_role");
        }
		
		$this->load->model('admin/role_model');
        $this->result = $this->role_model->get_role_details($id);
		
		$this->data['css']       = get_css('user_add');
        $this->data['js']        = get_js('user_add'); 
		$this->layout->view("admin/role/edit_role",$this->result);
	}
    
    
   
}
?>
