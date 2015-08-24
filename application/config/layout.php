<?php
 
//Config for Admin End
                                         
$config['layout']['admin']['js_dir']   = base_url('assets/admin/js');
$config['layout']['admin']['css_dir']  = base_url('assets/admin/css');
$config['layout']['admin']['img_dir']  = base_url('assets/admin/img');
$config['layout']['admin']['template'] = 'admin/layouts/admin';
$config['layout']['admin']['title']    = 'Jewellary';
$config['layout']['admin']['javascripts'] = array("demo-rtl","demo-skin-changer","jquery","bootstrap","jquery.nanoscroller.min","demo","modernizr.custom","classie","modalEffects","scripts","pace.min"); 
$config['layout']['admin']['stylesheets'] = array("bootstrap/bootstrap.min","libs/font-awesome","libs/nanoscroller","compiled/theme_styles","libs/token-input","libs/token-input-facebook");
$config['layout']['admin']['description'] = '';
$config['layout']['admin']['keywords']    = '';
$config['layout']['admin']['http_metas'] = array(
    'Content-Type' => 'text/html; charset=utf-8',
	'viewport'     => 'width=device-width, initial-scale=1.0',
    'author' => 'World Health Organization',
    'X-UA-Compatible' => 'IE=edge,chrome=1'
);


// Config for FrontEnd

$config['layout']['frontend']['js_dir']   = base_url('assets/frontend/js');
$config['layout']['frontend']['css_dir']  = base_url('assets/frontend/css');
$config['layout']['frontend']['img_dir']  = base_url('assets/frontend/images');
$config['layout']['frontend']['template'] = 'frontend/layouts/frontend';
$config['layout']['frontend']['title']    = 'Road Share :: Home';

$config['layout']['frontend']['javascripts'] = array("jquery-1.11.3.min","jquery-ui.min","jquery.datetimepicker","slides.min.jquery", "common");
 
$config['layout']['frontend']['stylesheets'] = array("jquery-ui","jquery.datetimepicker","stylesheet", "global");

$config['layout']['frontend']['description'] = 'Road Share :: Home';
$config['layout']['frontend']['keywords']    = 'Road Share :: Home';

$config['layout']['frontend']['http_metas'] = array(
    'Content-Type' => 'text/html; charset=utf-8',
	'viewport'     => 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0',
    'author' => 'Road Share',
    'X-UA-Compatible' => 'IE=edge,chrome=1'
);




?>
