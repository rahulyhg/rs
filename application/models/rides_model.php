<?php
safe_include("models/app_model.php");
class Rides_Model extends App_model {
    
    
    function __construct()
    {
        parent::__construct();
        $this->_table = 'rides';
    }

    
    function get_rides( $where = array() )
    {
    	$sql = "SELECT r.*,r.origin_name,rs.*,rd.* ,u.first_name,u.last_name,u.dob,u.gender,u.profile_img
    				FROM rides r 
    				JOIN ride_schedules rs ON(r.id=rs.ride_id AND rs.towards='up') 
    				JOIN ride_details rd ON(r.id=rd.ride_id) 
                    JOIN users u ON(u.id=r.user_id) 
    				WHERE rs.schedule_start_date='".$where['jdate']."' AND (r.origin_name LIKE '%".$where['origin_name']."%' 
    						OR r.origin_address LIKE '%".$where['origin_address']."%' 
    						OR r.origin_latlng LIKE '%".$where['origin_latlng']."%') 
							AND 
						   (r.dest_name LIKE '%".$where['dest_name']."%' 
    						OR r.dest_address LIKE '%".$where['dest_address']."%' 
    						OR r.dest_latlng LIKE '%".$where['dest_latlng']."%')
				";

		$sql .= ' UNION ALL ';

		$sql .=  "SELECT r.*,r.origin_name,rs.*,rd.*,u.first_name,u.last_name,u.dob,u.gender,u.profile_img 
    				FROM rides r 
    				JOIN ride_schedules rs ON(r.id=rs.ride_id AND rs.towards='down') 
    				JOIN ride_details rd ON(r.id=rd.ride_id)  
                    JOIN users u ON(u.id=r.user_id)
    				WHERE rs.schedule_start_date='".$where['jdate']."' AND (r.origin_name LIKE '%".$where['dest_name']."%' 
    						OR r.origin_address LIKE '%".$where['dest_address']."%' 
    						OR r.origin_latlng LIKE '%".$where['dest_latlng']."%') 
							AND 
						   (r.dest_name LIKE '%".$where['origin_name']."%' 
    						OR r.dest_address LIKE '%".$where['origin_address']."%' 
    						OR r.dest_latlng LIKE '%".$where['origin_latlng']."%')
				";

		return $this->db->query($sql)->result_array();
    }
    
}
?>