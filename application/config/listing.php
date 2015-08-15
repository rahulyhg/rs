<?php
/*
 * view - the path to the listing view that you want to display the data in
 * 
 * base_url - the url on which that pagination occurs. This may have to be modified in the 
 * 			controller if the url is like /product/edit/12
 * 
 * per_page - results per page
 * 
 * order_fields - These are the fields by which you want to allow sorting on. They must match
 * 				the field names in the table exactly. Can prefix with table name if needed
 * 				(EX: products.id)
 * 
 * OPTIONAL
 * 
 * default_order - One of the order fields above
 * 
 * uri_segment - this will have to be increased if you are paginating on a page like 
 * 				/product/edit/12
 * 				otherwise the pagingation will start on page 12 in this case 
 * 
 * 
 */
 
$config['admin_role_index'] = array(
	"view"		=> 	'admin/listing/listing',
	"init_scripts" => 'admin/listing/init_scripts',
	"advance_search_view" => 'admin/user/filter',
	"base_url"	=> 	'/admin/role/index/',
	"per_page"	=>	"2",
	"fields"	=> array(
							'name'=>array('name'=>'Role', 'data_type' => 'string', 'sortable' => TRUE, 'default_view'=>1),
							'created_date'=>array('name'=>'Created', 'data_type' => 'datetime', 'sortable' => TRUE, 'default_view'=>1),
							
						),
	"default_order"	=> "id",
	"default_direction" => "ASC"
);


$config['admin_user_index'] = array(
	"view"		=> 	'admin/listing/listing',
	"init_scripts" => 'admin/listing/init_scripts',
	"advance_search_view" => 'admin/user/filter',
	"base_url"	=> 	'/admin/user/index/',
	"per_page"	=>	"20",
	"fields"	=> array(
							'profile_name'=>array('name'=>'User', 'data_type' => 'string', 'sortable' => TRUE, 'default_view'=>1),
							'created_time'=>array('name'=>'Created', 'data_type' => 'datetime', 'sortable' => TRUE, 'default_view'=>1),
							'status'=>array('name'=>'Status', 'data_type' => 'string', 'sortable' => TRUE, 'default_view'=>1),
							'email'=>array('name'=>'Email', 'data_type' => 'string', 'sortable' => TRUE, 'default_view'=>1),
						),
	"default_order"	=> "id",
	"default_direction" => "DESC"
);

$config['admin_products_index'] = array(
	"view"		=> 	'admin/listing/listing',
	"init_scripts" => 'admin/listing/init_scripts',
	"advance_search_view" => 'admin/products/filter',
	"base_url"	=> 	'admin/products/index',
	"per_page"	=>	"20",
	"fields"	=> array(
							'product_name'=>array('name'=>'Product Name', 'data_type' => 'string', 'sortable' => TRUE, 'default_view'=>1),
							'price'=>array('name'=>'Price', 'data_type' => 'money', 'sortable' => TRUE, 'default_view'=>1),
							'created_time'=>array('name'=>'Created Date', 'data_type' => 'datetime', 'sortable' => TRUE, 'default_view'=>1),
							'favorites'=>array('name'=>'Favorites', 'data_type' => 'string', 'sortable' => TRUE, 'default_view'=>1),
							'likes'=>array('name'=>'Likes', 'data_type' => 'string', 'sortable' => TRUE, 'default_view'=>1),
							'followed'=>array('name'=>'Followed', 'data_type' => 'string', 'sortable' => TRUE, 'default_view'=>1),
							'buycount'=>array('name'=>'Buycount', 'data_type' => 'string', 'sortable' => TRUE, 'default_view'=>1)
						),	
	"default_order"	=> "id",
	"default_direction" => "DESC"
);

$config['admin_message_index'] = array(
	"view"		=> 	'admin/listing/listing',
	"init_scripts" => 'admin/listing/init_scripts',
	"advance_search_view" => 'admin/message/filter',
	"base_url"	=> 	'admin/message/index/',
	"per_page"	=>	"20",
	"fields"	=> array(
							'name'=>array('name'=>'Title', 'data_type' => 'string', 'sortable' => TRUE, 'default_view'=>1),
							'message'=>array('name'=>'Message', 'data_type' => 'string', 'sortable' => TRUE, 'default_view'=>1),
							'type'=>array('name'=>'Type', 'data_type' => 'string', 'sortable' => TRUE, 'default_view'=>1),
							'created_time'=>array('name'=>'Created Date', 'data_type' => 'datetime', 'sortable' => TRUE, 'default_view'=>1),
							
						),
	"default_order"	=> "id",
	"default_direction" => "DESC"
);