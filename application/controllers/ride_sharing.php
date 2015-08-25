<?php if(!defined("BASEPATH")) exit("No direct script access allowed");

safe_include("controllers/app_controller.php");
class Ride_sharing extends App_Controller {
    function __construct()
    {
        parent::__construct();       
        
        $this->load->model('rides_model');
    }

    public function index()
    {
        if( !count($_POST) )
            redirect('');
        
        $rides = $this->rides_model->get_rides( $_POST );
        // print_r($rides);die;
        $this->data['rides']    = $rides;
        $this->data['data']     = $_POST;
        $this->layout->view('frontend/ride_sharing');
    }

    function view($schedule_id='')
    {
        try
            {
                $this->data['message']='';

                if(!(int)$schedule_id)
                    throw new Exception("Not a valid ride!");

                $this->data['view_data'] = $this->rides_model->get_ride_view($schedule_id);

            }
            catch(Exception $e)
            {
                $this->data['message'] = $e->getMessage();
            }    

        $this->layout->view('frontend/ride_view');    
    }

    function get_rules()
    {
        
    }
    
}
?>
