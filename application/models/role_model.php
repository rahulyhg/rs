<?php
safe_include("models/app_model.php");
class Role_Model extends App_model {
    
    
    function __construct() 
    {
        parent::__construct();
        $this->_table = 'jwb_roles';
    }
   

   function listing()
    {
        $this->_fields = " *";
        
        //from
        $this->db->from($this->_table);
        
        //joins
        
        
        //where
        foreach ($this->criteria as $key => $value) 
        {
            if( !is_array($value) && strcmp($value, '') === 0 )
                continue;

            switch ($key)
            {
                case 'name':
                    $this->db->like($key, $value);
                break;
            }
        }
        
        
        return parent::listing();
    }

   function get_roles()
   {
      $this->db->select('*');
      $this->db->from('jwb_roles');
      return $this->db->get()->result_array();
   } 
    
   public function add_role($data = "" )
   {
	   //print_r($data);exit;
	     return $this->db->insert('jwb_roles', array('name' => $data["role"]));
   }
   
   
   public function get_all_role()
   {
	   $this->db->select('*');
        $this->db->from('jwb_roles');
        return $this->db->get()->result_array();
   }
   
   
   public function edit_role($data = "")
   {
	   $data_val = array(
               'name' => $data['role']
                     );

		$this->db->where('id', $data['id']);
		$this->db->update('jwb_roles', $data_val); 
   }
   
   
   public function get_role_details($id = "")
   {
	  $this->db->select('*');
      $this->db->from('jwb_roles');
      $this->db->where('id', $id);
      $query = $this->db->get()->result_array();
      return $query;
   }
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
}
?>
