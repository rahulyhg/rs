<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

safe_include("controllers/admin/admin_controller.php");

class Message extends Admin_controller {
    
    function __construct() 
    {
         parent::__construct();

        $this->load->model('message_model');
    }

	public function index()
	{
      
		//$this->output->enable_profiler(TRUE);

        $this->layout->add_javascripts(array('listing', 'rwd-table'));  

        $this->load->library('listing');

        
        $this->simple_search_fields = array(
			                                'name' => 'Message Title',
			                                'message' => 'Message',                                               
			                                'type' => 'Type'
                                               
        );
         
        $this->_narrow_search_conditions = array("start_date", "end_date", "customer", "order_status", "sales_channel", "type","followup","fraudulent","next_due_start_date","next_due_end_date","paid_status","overdue","ship_start_date","ship_end_date","orders_at_risk");
        
        $str = '<a href="'.site_url('admin/message/edit/{id}').'" data-toggle="tooltip" data-original-title="Edit" class="table-link">
                    <span class="fa-stack">
                        <i class="fa fa-square fa-stack-2x"></i>
                        <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                    </span>
                </a>
                <a class="table-link danger" href="'.site_url('admin/message/delete/{id}').'" onclick = "return confirm(\'Are you sure wants to delete this Message!\')" data-toggle="tooltip" data-original-title="Delete">
					<span class="fa-stack">
						<i class="fa fa-square fa-stack-2x"></i>
						<i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
					</span>
				</a>';
 
        $this->listing->initialize(array('listing_action' => $str));

        $listing = $this->listing->get_listings('message_model', 'listing');

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
        
        $this->layout->view("admin/message/list");

	}
	
	
	
	public function add($edit_id=0)
	{

		$this->layout->add_javascripts(array('jquery.tokeninput','message'));

        $form = $this->input->post();
        $form = $this->security->xss_clean($form);

        
        if(isset($form['edit_id']))
            $edit_id = $form['edit_id'];
		
		$this->data['form_data'] = array("id"=>"","name"=>"","message"=>"","type"=>'site',"users"=>"");
        
        $edit_data = $this->message_model->get_where(array('id'=>$edit_id))->row_array();

        if($edit_data)
            $this->data['form_data'] = $edit_data;

        if(isset($form['users']))
        	$this->data['form_data']['users'] = $form['users'];

        $this->data["sel_users"] = "";

        if(!empty($this->data['form_data']['users'])){


        	$user_ids = explode(',',$this->data['form_data']['users']); 

        	$users = $this->message_model->get_single_message_details($user_ids);

        	foreach($users as $s){

				$response[] = array( "id" => $s["id"], "name" => $s["user_name"]);		
			}

			$this->data["sel_users"] = json_encode($response);
        }


        $this->form_validation->set_rules($this->_validation_rules());

        
        if($this->form_validation->run()) {

        	$ins_data = array();            
            $ins_data['name']         =   $form['name'];
            $ins_data['message']      =   $form['message'];
            $ins_data['type']         =   $form['type'];
            $ins_data['users']        =   $form['users'];
			$ins_data['created_id']   = get_current_user_id();
            $ins_data['created_time'] = str2DBDT();

            if(!empty($edit_id))
            {
                $ins_data['updated_id']     = get_current_user_id();
                $ins_data['updated_time']   = str2DBDT();

                $this->message_model->update(array('id'=>$edit_id),$ins_data);
                $this->service_message->set_flash_message("record_update_success");  
            }
            else
            {
                $this->message_model->insert($ins_data);
                $this->service_message->set_flash_message("record_insert_success");
            }
            
			redirect("admin/message");
        }
		$this->layout->view("admin/message/add");
		
	}
	
	
	public function _validation_rules()
	{
		$rules = array(array('field' => 'name', 'label' => 'Title', 'rules' => 'trim|required|xss_clean'),
                       array('field' => 'message', 'label' => 'Message', 'rules' => 'trim|required|xss_clean'),
                       array('field' => 'type', 'label' => 'Type', 'rules' => 'trim|required|xss_clean'),
                       array('field' => 'users', 'label' => 'Users', 'rules' => 'trim|xss_clean')
                      );
		return  $rules;
	}
	
	public function delete($id = 0)
	{
		if(empty($id))
			return FALSE;

		$this->message_model->delete(array('id'=>$id));
        $this->service_message->set_flash_message("record_delete_success");

		redirect("admin/message");
	}
	
	
	public function auto_complete()
	{
		//echo $val;exit;
		$val  = $this->input->post('search_key');
		
		$this->load->model('admin/message_model');
		$result =  $this->message_model->auto_complete_result($val);
		
		foreach($result as $search){
				$response[] = array( "id" => $search["id"], "name" => $search["user_name"]);
			}
			echo json_encode($response);
			exit;
			
		
	}
	
	
	
	
	
	
	
	
	
	
	
    
}


