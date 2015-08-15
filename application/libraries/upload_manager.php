 <?php if (!defined('BASEPATH')) exit('No direct script access allowed');

 class Upload_manager {

 	private $CI;
 	public $error = '';
 	public $upload_data = array();
 	public $config = array(	);

 	function __construct( $params = array() )
 	{
 		$this->CI =& get_instance();

 		$this->CI->load->library('upload');


 		$this->config['img'] = array(
 								'upload_path' => './asset/images/',
 								'allowed_types' => 'jpeg|jpg|jpe|png',
 								'max_size'	=> '0'
 				);

 		$this->config['audio'] = array(
 								'upload_path' => './asset/audio/',
 								'allowed_types' => 'mp3|mp2|mpga|mid',
 								'max_size'	=> '0'
 				);

 	}


 	function upload( $type = 'img', $fname = 'userfile')
 	{
 		if( !isset($this->config[$type]) )
 		{
 			$this->error = 'Invalid Upload Type.';
 			return FALSE;
 		}

 		$this->CI->upload->initialize($this->config[$type]);

 		if ( ! $this->CI->upload->do_upload($fname) )
        {
        	$this->error = $this->CI->upload->display_errors();
        	return FALSE;
        }
      	else
        {
        	$this->upload_data = $this->CI->upload->data();
        	return TRUE;
        }

 	}


 }