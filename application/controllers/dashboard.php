<?php if(!defined("BASEPATH")) exit("No direct script access allowed");

safe_include("controllers/app_controller.php");
class Dashboard extends App_Controller {
    public $user_id;
    function __construct()
    {
        parent::__construct();       
        $this->user_id = get_current_user_id();
    }

    public function index()
    {
    	
        $this->data['tmenu'] = $this->load->view('frontend/_partials/top-menu', $this->data, TRUE);
        $this->data['smenu'] = $this->load->view('frontend/_partials/profile-left-menu', $this->data, TRUE);
        
        $this->layout->view('frontend/dashboard/dashboard');

    }

    public function profile( $type = 'general' )
    {

        $this->data['type']  = $type;
        $this->data['tmenu'] = $this->load->view('frontend/_partials/top-menu', $this->data, TRUE);
        $this->data['smenu'] = $this->load->view('frontend/_partials/profile-left-menu', $this->data, TRUE);
        

        switch ($type) {

            case 'general':

                $this->form_validation->set_rules($this->get_rules($type));

                $this->data['general'] = $this->user_model->get_where(array('id' => $this->user_id))->row_array();

                if($this->form_validation->run())
                {
                    $form = $this->input->post();
                    $insert = array();
                    $insert['gender']       = $form['gender'];
                    $insert['first_name']   = $form['first_name'];
                    $insert['last_name']    = $form['last_name'];
                    $insert['email']        = $form['email'];
                    $insert['phone']        = $form['phone'];
                    $insert['dob']          = $form['dob'];
                    $insert['bio']          = $form['bio'];

                    $this->user_model->update(array('id' => $this->user_id), $insert);

                    $this->service_message->set_message('custom_message_success', 'Updated successfully.');

                   

                }

                break;
            case 'picture':
                $this->layout->add_javascripts(array('SimpleAjaxUploader'));
                $this->init_scripts = array('frontend/dashboard/ajax_uploader_script');               
                
                $this->data['general'] = $this->user_model->get_where(array('id' => $this->user_id))->row_array();
                

                break;


            
            default:
                # code...
                break;
        }

        $this->layout->view('frontend/dashboard/'.$type);
    }

    public function rides_offered()
    {
        
        $this->data['tmenu'] = $this->load->view('frontend/_partials/top-menu', $this->data, TRUE);
        
        $this->layout->view('frontend/dashboard/dashboard');

    }

    public function messages()
    {
        
        $this->data['tmenu'] = $this->load->view('frontend/_partials/top-menu', $this->data, TRUE);
        $this->data['smenu'] = $this->load->view('frontend/_partials/profile-left-menu', $this->data, TRUE);
        
        $this->layout->view('frontend/dashboard/dashboard');

    }

    public function emails()
    {
        
        $this->data['tmenu'] = $this->load->view('frontend/_partials/top-menu', $this->data, TRUE);
        $this->data['smenu'] = $this->load->view('frontend/_partials/profile-left-menu', $this->data, TRUE);
        
        $this->layout->view('frontend/dashboard/dashboard');

    }

    function upload_photo()
    {
        $this->load->library('FileUpload');
        $upload_dir = 'assets/uploads/users/';


        $uploader = new FileUpload('uploadfile');

        // Handle the upload
        $result = $uploader->handleUpload($upload_dir);
        

        if (!$result) {
          exit(json_encode(array('success' => false, 'msg' => $uploader->getErrorMsg())));  
        }

        $update = array('profile_img' => $uploader->getFileName());
        $this->user_model->update(array('id' => $this->user_id), $update);
        echo json_encode(array('success' => true, 'fname' => $uploader->getFileName()));

        exit;
    }

    function upload_progress()
    {
        // This "if" statement is only necessary for CORS uploads -- if you're
        // only doing same-domain uploads then you can delete it if you want
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        if (isset($_REQUEST['progresskey'])) {
            $status = apc_fetch('upload_'.$_REQUEST['progresskey']);
        } else {
            exit(json_encode(array('success' => false)));
        }

        $pct = 0;
        $size = 0;

        if (is_array($status)) {

            if (array_key_exists('total', $status) && array_key_exists('current', $status)) {

                if ($status['total'] > 0) {
                    $pct = round(($status['current'] / $status['total']) * 100);
                    $size = round($status['total'] / 1024);
                }
            }
        }

        echo json_encode(array('success' => true, 'pct' => $pct, 'size' => $size));
    }

    function get_rules( $type = 'general' )
    {
        $rules = array();

        if( $type == 'general' )
        {
            $rules = array(
                    array('field' => 'gender', 'label' => 'Gender', 'rules' => 'trim|required'),
                    array('field' => 'first_name', 'label' => 'First Name', 'rules' => 'trim|required|max_length[255]'),
                    array('field' => 'last_name', 'label' => 'Last Name', 'rules' => 'trim|required|max_length[255]'),
                    array('field' => 'email', 'label' => 'Email Address', 'rules' => 'trim|required|valid_email'),
                    array('field' => 'dob', 'label' => 'Date of Birth', 'rules' => 'trim|required'),
                    array('field' => 'phone', 'label' => 'Mobile Phone', 'rules' => 'trim'),
                    array('field' => 'bio', 'label' => 'Bio', 'rules' => 'trim'),                    
                );
        }
        

        return $rules;
    }


    
    
    
	
	
}
?>
