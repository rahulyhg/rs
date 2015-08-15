<?php

safe_include("models/app_model.php");

class Views_model extends App_Model {

    protected $_table = 'jwb_views';
    
    function __construct()
    {
        parent::__construct();

    }
    
    function listing()
    {
        $this->_fields = "*";
        
        //from
        $this->db->from($this->_table);
        
        //joins
        
        
        //where
        foreach ($this->criteria as $key => $value){

        	if( !is_array($value) && strcmp($value, '') === 0 )
    			continue;
    		
    		switch ($key)
    		{
     			case 'product_name':
                    $this->db->like("jwb_views.product_name",$value);
                    break;
                case 'description':
    				 $this->db->like("jwb_views.description",$value);
    				 break;
                case 'price':
                    $this->db->like("jwb_views.price",$value);
                    break;
            }        
        }
        
        
        return parent::listing();
    }
}
?>