<?php if(!defined("BASEPATH")) exit("No direct script access allowed");

safe_include("controllers/app_controller.php");
class Home extends App_Controller {
    function __construct()
    {
        parent::__construct();       
        $this->init_scripts = array('frontend/_partials/google_script'); 
    }

    public function index()
    {
    	
        $this->layout->add_javascripts(array('home'));
        
        $this->layout->view('frontend/home/home');
    }
    
    
    public function how_it_works()
    {
		$this->layout->view('frontend/home/how_it_works');
		
	}
	
	public function faq()
    {
		$this->layout->view('frontend/home/faq');	
		
	}

    function offer_seats()
    {
        if( !is_logged_in() )
            redirect('login');

        if( $this->input->is_ajax_request() )
        {
            $this->load->model('rides_model');

            $rides = array();
            $rides['user_id']       = get_current_user_id();
            $rides['origin_name']   = $this->input->post('origin_name');
            $rides['origin_latlng'] = $this->input->post('origin_latlng');
            $rides['origin_address']= $this->input->post('origin_address');
            $rides['dest_name']     = $this->input->post('dest_name');
            $rides['dest_latlng']   = $this->input->post('dest_latlng');
            $rides['dest_address']  = $this->input->post('dest_address');
            $rides['schedule_type'] = 'OT';
            $rides['ride_type']     = (int)$this->input->post('ride_type');

            $ride_id = $this->rides_model->insert($rides);
            //echo $ride_id;die;
            $ride_details = [];
            $ride_details['ride_id']                = $ride_id;
            $ride_details['seat_count']             = $this->input->post('seat_count');
            $ride_details['ride_details']           = $this->input->post('ride_details');
            $ride_details['luggage']                = $this->input->post('luggage');
            $ride_details['schedule_flexibility']   = $this->input->post('schedule_flexibility');
            $ride_details['detour_flexibility']     = $this->input->post('detour_flexibility');
            $ride_details['total_dist']             = $this->input->post('total_dist');
            $ride_details['total_time']             = $this->input->post('total_time');

            $this->rides_model->insert($ride_details, 'ride_details');
            print_r($rides);die;
        }
        $this->layout->view('frontend/home/offer_seats');
    }
	
	
}
?>
