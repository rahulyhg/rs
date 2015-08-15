<?php if(!defined("BASEPATH")) exit("No direct script access allowed");

safe_include("controllers/admin/admin_controller.php");

class Uploads extends Admin_controller {
    
    public $data = array();

    private $upload_path = './assets/uploads/';

    function __construct() {
        parent::__construct();

    }

   function do_upload(){

        try
        {
            $form = $this->input->post(NULL, TRUE);

            if(!isset($form['upload_folder']))
                 throw new Exception("Upload folder is empty!");

            $config['upload_path'] = $this->upload_path.$form['upload_folder'].'/';
            $config['allowed_types'] = (isset($form['types']) && !empty($form['types']))?$form['types']:'gif|jpg|png|jpeg';
            $config['max_size'] = '10000';
            $config['max_width']  = '800';
            $config['max_height']  = '600';

            $this->load->library('upload', $config);

            if(!$this->upload->do_upload('upload_image'))            
               throw new Exception($this->upload->display_errors());
               
            $files = $this->upload->data();  
            $this->data['fileuploaded']   = $files['file_name'];               

        }
        catch(Exception $e)
        {
            $this->data['error'] = $e->getMessage();
            
        }     

        echo json_encode($this->data);
        exit;

   }
}

?>