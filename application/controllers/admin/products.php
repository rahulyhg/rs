<?php if(!defined("BASEPATH")) exit("No direct script access allowed");

safe_include("controllers/admin/admin_controller.php");

class Products extends Admin_controller {
    
    public $data = array();

    function __construct() {

        parent::__construct();

        $this->layout->add_javascripts(array('fileinput.min','fileinput'));
        $this->layout->add_stylesheets(array('fileinput.min','fileinput'));
        $this->load->model('product_model');
    }
    
    public function index()
    {
        //$this->output->enable_profiler(TRUE);

        $this->layout->add_javascripts(array('listing', 'rwd-table'));  

        $this->load->library('listing');

        
        $this->simple_search_fields = array(
                                                'product_name' => 'Product Name',                                                
                                                'description' => 'Description',
                                                'price' => 'Price'
                                               
        );
         
        $this->_narrow_search_conditions = array("start_date", "end_date", "customer", "order_status", "sales_channel", "type","followup","fraudulent","next_due_start_date","next_due_end_date","paid_status","overdue","ship_start_date","ship_end_date","orders_at_risk");
        
        $str = '<a href="'.site_url('admin/products/edit/{id}').'" class="table-link">
                    <span class="fa-stack">
                        <i class="fa fa-square fa-stack-2x"></i>
                        <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                    </span>
                </a>';
 
        $this->listing->initialize(array('listing_action' => $str));

        $listing = $this->listing->get_listings('product_model', 'listing');

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
        
        $this->layout->view("admin/product/list");
       
    }

    function add($edit_id=0){

        $this->layout->add_javascripts(array('product'));

        $form = $this->input->post();
        //print_r($form);exit;
        $form = $this->security->xss_clean($form);
        
        if(isset($form['upcoming_product'])) { 
			$form['upcoming_product'] = $form['upcoming_product'];	
		}
		else { 
			$form['upcoming_product'] = "0";
		}

        if(isset($form['edit_id']))
            $edit_id = $form['edit_id'];

        $this->form_validation->set_rules($this->_validation_rules());

        $this->data['form_data'] = array("id"=>"","product_name"=>"","description"=>"","product_image"=>"","upcoming_product" => "", "buylink" => "","price" => "");
        
        $edit_data = $this->product_model->get_where(array('id'=>$edit_id))->row_array();

        if($edit_data)
            $this->data['form_data'] = $edit_data;

        $this->data['img_url']   = site_url('assets/uploads/products/'.$this->data['form_data']['product_image']);

        if($this->form_validation->run()) {

            $ins_data = array();
            $ins_data['product_name']       = $form['product_name'];
            $ins_data['description']        = $form['description'];
            $ins_data['product_image']      = $form['product_image'];
            $ins_data['upcoming_product']   = $form['upcoming_product'];
            $ins_data['buylink']            = $form['buylink'];
            $ins_data['price']              = $form['price'];
            $ins_data['created_id']         = get_current_user_id();
            $ins_data['created_time']       = str2DBDT();

            if(!empty($edit_id))
            {
                $ins_data['updated_id']     = get_current_user_id();
                $ins_data['updated_time']   = str2DBDT();

                $this->product_model->update(array('id'=>$edit_id),$ins_data);
                $this->service_message->set_flash_message("record_update_success");  

                //log
                actionLogAdd('product', $edit_id, "Product#$edit_id ({$form['product_name']}) record has been updated.");
            }
            else
            {
                $product_id = $this->product_model->insert($ins_data);
                $this->service_message->set_flash_message("record_insert_success");

                //log
                actionLogAdd('product', $product_id, "Product#$product_id ({$form['product_name']}) record has been created.");
            }

                redirect('admin/products');
        }

        $this->layout->view("admin/product/add");

    }

    public function _validation_rules()
    {

    $rules = array(array('field' => 'product_name', 'label' => 'Product Name', 'rules' => 'trim|required|xss_clean'),
                   array('field' => 'description', 'label' => 'Description', 'rules' => 'trim|required|xss_clean'),
                   array('field' => 'upcoming_product', 'label' => 'Upcoming product', 'rules' => 'trim|xss_clean'),
                   array('field' => 'buylink', 'label' => 'Buylink', 'rules' => 'trim|required|xss_clean'),
                   array('field' => 'price', 'label' => 'Price', 'rules' => 'trim|required|xss_clean|numeric|max_length[7]')
                  );

    return  $rules;
   } 

}

//array('field' => 'product_image', 'label' => 'Product image', 'rules' => 'trim|required|xss_clean'),

?>
