<?php
safe_include("models/app_model.php");
class Rides_Model extends App_model {
    
    
    function __construct()
    {
        parent::__construct();
        $this->_table = 'rides';
    }

    

    
}
?>