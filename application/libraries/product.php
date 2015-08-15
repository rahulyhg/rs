<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Product {

	protected $CI;


	function __construct($type = '')
    {
        $this->CI =& get_instance();

        $this->CI->load->model(array('likes_model', 'favourites_model','collection_model'));
    }

    function init_check($product_id = 0, $user_id = 0)
    {
    	try
    	{
    		if(!$product_id)
    			throw new Exception("Invalid Product ID.");

    		if(!$user_id)
    			throw new Exception("Invalid User ID.");

    		if(!is_valid_product($product_id))
				throw new Exception("The Product ID#$product_id does not exist.");

			if(!is_valid_product($user_id))
				throw new Exception("The User ID#$user_id does not exist.");

			return TRUE;
    	}
    	catch(Exception $e)
		{
			//set error message if the variable is available
			$this->CI->error_message = $e->getMessage();
			return FALSE;
		}
    }

    function like($product_id = 0, $user_id = 0)
    {
		//echo $user_id;exit;

    	try
    	{
    		//Transaction starts here
			$this->CI->db->trans_begin();
				
			

			$insert = array(
	    				'product_id' => $product_id,
	    				'user_id'	 => $user_id
	    				);
	    	$result = $this->CI->likes_model->insert($insert);
	    	//print_r($result);exit;

	    	//update count of likes of current product
	    	$this->update_count($product_id, 'likes');

		    //now end the trnascation.
			if ($this->CI->db->trans_status() === FALSE)
			{
				throw new Exception("Query failed.");
			}
			else
			{
				$this->CI->db->trans_commit();
			}	
			return TRUE;	
    	}
    	catch(Exception $e)
		{
			$this->CI->db->trans_rollback();
			//set error message if the variable is available
			if(isset($this->CI->error_message))
				$this->CI->error_message = $e->getMessage();
			return FALSE;
		}
    	
    }

    function unlike($product_id = 0, $user_id = 0)
    {

    	try
    	{
    		//Transaction starts here
			$this->CI->db->trans_begin();				

			/*if(!init_check($product_id = 0, $user_id = 0))		
				throw new Exception($this->CI->error_message); */

			$delete = array(
	    				'product_id' => $product_id,
	    				'user_id'	 => $user_id
	    				);
	    	$this->CI->likes_model->delete($delete);

	    	//update count of likes of current product
	    	$this->update_count($product_id, 'likes');

		    //now end the trnascation.
			if ($this->CI->db->trans_status() === FALSE)
			{
				throw new Exception("Query failed.");
			}
			else
			{
				$this->CI->db->trans_commit();
			}	
			return TRUE;	
    	}
    	catch(Exception $e)
		{
			$this->CI->db->trans_rollback();
			//set error message if the variable is available
			if(isset($this->CI->error_message))
				$this->CI->error_message = $e->getMessage();
			return FALSE;
		}
    	
    }

    function update_count($product_id = 0, $type = null)
    { 
    	switch ($type) 
    	{
    		case 'likes':
    			$update_sql = "UPDATE `jwb_products` p 
	    						SET likes=(
	    									SELECT count(id) 
	    										FROM `jwb_likes` l 
	    										where l.product_id=p.id 
	    									) 
	    						WHERE p.id=?";

	    		$this->CI->db->query($update_sql, array($product_id));
    			break;
    		
    		case 'views':
    			$update_sql = "UPDATE `jwb_products` p 
	    						SET views=(
	    									SELECT count(id) 
	    										FROM `jwb_views` v 
	    										where v.product_id=p.id 
	    									) 
	    						WHERE p.id=?";

	    		$this->CI->db->query($update_sql, array($product_id));
    			break;


    		case 'favourites':
    			$update_sql = "UPDATE `jwb_products` p 
	    						SET favorites=(
	    									SELECT count(f.id)
	    										FROM `jwb_favourites` f 
	    										JOIN `jwb_collections` c 
	    											ON(f.collection_id=c.id) 
	    										WHERE f.product_id=p.id 
	    										
	    									) 
	    						WHERE p.id=?";
	    						//GROUP BY c.user_id print $update_sql; echo "--"; echo $product_id;exit;

	    		$this->CI->db->query($update_sql, array($product_id));
    			break;

    		default:
    			# code...
    			break;
    	}
    	

	    return TRUE;
    }


    function log_view($product_id = 0, $user_id = 0)
    {

    	try
    	{
    		//Transaction starts here
			$this->CI->db->trans_begin();				

			if(!init_check($product_id = 0, $user_id = 0))		
				throw new Exception($this->CI->error_message);

			$insert = array(
	    				'product_id' => $product_id,
	    				'user_id'	 => $user_id
	    				);
	    	$this->CI->views_model->insert($insert);

	    	//update count of likes of current product
	    	$this->update_count($product_id, 'views');

		    //now end the trnascation.
			if ($this->CI->db->trans_status() === FALSE)
			{
				throw new Exception("Query failed.");
			}
			else
			{
				$this->CI->db->trans_commit();
			}		
    	}
    	catch(Exception $e)
		{
			$this->CI->db->trans_rollback();
			//set error message if the variable is available
			if(isset($this->CI->error_message))
				$this->CI->error_message = $e->getMessage();
			return FALSE;
		}
    	
    }

    function add_to_favourites($product_id = 0, $collection_id = 0,$user_id = 0)
    { //echo $collection_id;exit;

    	try
    	{
    		//Transaction starts here
			$this->CI->db->trans_begin();				

			

			$insert = array(
	    				'product_id' => $product_id,
	    				'collection_id'	 => $collection_id,
	    				'user_id'	 => $user_id
	    				);
	    	$this->CI->favourites_model->insert($insert);

	    	//update count of likes of current product
	    	$this->update_count($product_id, 'favourites');

		    //now end the trnascation.
			if ($this->CI->db->trans_status() === FALSE)
			{
				throw new Exception("Query failed.");
			}
			else
			{
				$this->CI->db->trans_commit();
			}	
			return TRUE;	
    	}
    	catch(Exception $e)
		{
			$this->CI->db->trans_rollback();
			//set error message if the variable is available
			if(isset($this->CI->error_message))
				$this->CI->error_message = $e->getMessage();
			return FALSE;
		}
    	
    }

    function create_collection($user_id = 0, $collection_name = "")
    { //print $collection_name;exit;

    	try
    	{
    		//Transaction starts here
			$this->CI->db->trans_begin();				

			
			$insert = array(
	    				'user_id' => $user_id,
	    				'name'	 => $collection_name
	    				);
	    	$this->CI->collection_model->insert($insert);

	    	
		    //now end the trnascation.
			if ($this->CI->db->trans_status() === FALSE)
			{
				throw new Exception("Query failed.");
			}
			else
			{
				$this->CI->db->trans_commit();
			}	
			return TRUE;	
    	}
    	catch(Exception $e)
		{
			$this->CI->db->trans_rollback();
			//set error message if the variable is available
			if(isset($this->CI->error_message))
				$this->CI->error_message = $e->getMessage();
			return FALSE;
		}
    	
    }


    function get_last_favourited_by($product_id = 0)
    {
    	$sql = "SELECT * 
					FROM `jwb_favourites` f 
					JOIN `jwb_collections` c 
						ON(f.collection_id=c.id) 
					JOIN `jwb_users` u 
						ON(u.id=c.user_id)
					WHERE f.product_id=?
					ORDER BY f.id DESC LIMIT 1";

		return $this->CI->db->query($sql, array($product_id))->row_array();
    }


    function get_deatils($product_id = 0)
    {
    		
    }


}
