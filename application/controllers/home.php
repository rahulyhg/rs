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

    
	
	
}
?>
