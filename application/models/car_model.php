<?php
safe_include("models/app_model.php");
class Car_Model extends App_model {
    
    
  function __construct() 
  {
      parent::__construct();
      $this->_table = 'cars';
  }
   

  function get_cars( )
  {
    $this->db->select('*');
    $this->db->from('cars');
    return $this->db->get()->result_array();
  } 

  function get_makes()
  {
    $this->db->select('*');
    $this->db->from('car_makes');
    return $this->db->get()->result_array();
  } 
    
  function get_models( $make_id = null )
  {
    $this->db->select('*');
    if( !is_null($make_id) )
      $this->db->where('make_id', $make_id);

    $this->db->from('car_models');
    return $this->db->get()->result_array();
  }
   
   
  function get_colours()
  {
    $this->db->select('*');
    $this->db->from('car_colours');
    return $this->db->get()->result_array();
  }
   
   
  function get_types()
  {
    $this->db->select('*');
    $this->db->from('car_types');
    return $this->db->get()->result_array();
  }

  function get_comforts()
  {
    $this->db->select('*');
    $this->db->from('car_comfort');
    return $this->db->get()->result_array();
  }
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
}
?>
