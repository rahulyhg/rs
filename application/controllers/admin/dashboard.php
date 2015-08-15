<?php if(!defined("BASEPATH")) exit("No direct script access allowed");
safe_include("controllers/admin/admin_controller.php");
class Dashboard extends Admin_Controller {
    
    function __construct()
    {
        parent::__construct();

        $this->load->model('dashboard_model');
    }
    
    public function index()
    {
        $this->layout->add_javascripts(array('jsapi','home'));
            
        $this->data['home_data'] = $this->dashboard_model->get_home_data();
        
        $this->data['user_data'] = $this->session->userdata('user_data');
        $this->service_message->set_flash_message('login_success','welcome admin'); 
        $this->layout->view('admin/home');
       
    }

    function product_chart(){

        try
        { 
            $rep_data = $this->dashboard_model->get_products_report();

            if(empty($rep_data))
                throw new Exception("No Report Data");

            $val_data = array();

            foreach($rep_data as $key => $val){

                switch($val['type']){

                    case 'favorites':
                        $data['favorites'][] =   array($val['product_name'],$val['count'],'color:'.$this->get_random_color());
                        break; 

                    case 'likes':
                        $data['likes'][]    =   array($val['product_name'],$val['count'],'color:'.$this->get_random_color());
                        break;   

                    case 'followed':
                        $data['followed'][] =   array($val['product_name'],$val['count'],'color:'.$this->get_random_color());
                        break;  

                    case 'buycount':
                        $data['buycount'][] =  array($val['product_name'],$val['count'],'color:'.$this->get_random_color());
                        break; 

                    default:
                           break;             
                }
            }

            $val_data['records']['favorites'] = $this->combine_title_data($data['favorites'],'Products','Favorites');
            $val_data['records']['likes']     = $this->combine_title_data($data['likes'],'Products','Likes');
            $val_data['records']['followed']  = $this->combine_title_data($data['followed'],'Products','Followed');
            $val_data['records']['buycount']  = $this->combine_title_data($data['buycount'],'Products','Buycount');

            $val_data['status'] = 'success';

        }
        catch(Exception $e)
        {
            $val_data['message'] = $e->getMessage();
            
        }     
        echo json_encode($val_data);
        exit;
    
    }

    function user_chart(){

        try
        { 
            $val_data = array();

            $daterange = array('today','lastweek','lastmonth','lastyear');

            $where=array();

            foreach($daterange as $intr){

                $get_range = date_ranges($intr);

                $where[$intr] = array('start'=>$get_range['lowdateval'].' 00:00:00','end'=>$get_range['highdateval'].' 23:59:59');
            }

            $rep_data = $this->dashboard_model->get_user_report($where);


            if(empty($rep_data))
                throw new Exception("No Report Data");

            $data = array();

            foreach($rep_data as $key => $val){

                $data[] = array(ucfirst($val['type']),$val['count'],'color:'.$this->get_random_color());
            }   

            $title = array(array('label' => 'Users', 'type' => 'string'),array('label' => 'Total Users', 'type' => 'number'),array('role'=>'style'));
            array_unshift($data,$title);

            $val_data['records'] = $data;
            
            $val_data['status'] = 'success';

        }
        catch(Exception $e)
        {
            $val_data['message'] = $e->getMessage();
            
        }     
        echo json_encode($val_data);
        exit;
    
    }

    function combine_title_data($data,$title,$val_title){
        
        $tit = array(array('label' => $title, 'type' => 'string'),array('label' => $val_title, 'type' => 'number'),array('role'=>'style'));
        array_unshift($data,$tit);
        
        return $data;


    }

    function get_random_color()
    {

        $c = '';

        for ($i = 0; $i<6; $i++)
        {
            $c .=  dechex(rand(0,15));
        }
        return "#$c";
    } 
}

?>