<?php
safe_include("models/app_model.php");
class Log_model extends App_model {
    
    
    function __construct()
    {
        parent::__construct();
        $this->_table = 'jwb_log';
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
                case 'action':
                    $this->db->like($key, $value);
                break;
            }
        }
        
        
        return parent::listing();
    }
    
    
    function add($type = null, $id = null, $action = '')
    {

        $data = array(
                'action' => $action,
                'action_id' => $id,
                'line' => $type,
                'created_id' => get_current_user_id(),
                'updated_id' => get_current_user_id(),
                'created_time' => date('Y-m-d H:i:s'),
                'updated_time' => date('Y-m-d H:i:s') 
            );

        $this->insert($data);
    }
}
?>