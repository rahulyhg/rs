<?php

function get_makes()
{
    $CI = & get_instance();
    $CI->load->model('car_model');
    $records = $CI->car_model->get_makes();

    $makes = array();
    foreach ($records as $make) 
    {
        $makes[$make['id']] = $make['name'];
    }

    return $makes;
}


function get_models( $make_id = null, $all = true )
{
    $CI = & get_instance();
    $CI->load->model('car_model');
    $records = $CI->car_model->get_models( $make_id );
    $models = array();
    foreach ($records as $model) 
    {
        if($all)
            $models[$model['make_id']][$model['id']] = $model['name'];
        else
            $models[$model['id']] = $model['name'];
    }

    return $models;
}

function get_colours(  )
{
    $CI = & get_instance();
    $CI->load->model('car_model');
    $records = $CI->car_model->get_colours(  );

    $colours = array();
    foreach ($records as $colour) 
    {
        $colours[$colour['id']] = $colour['name'];
    }

    return $colours;
}


function get_types(  )
{
    $CI = & get_instance();
    $CI->load->model('car_model');
    $records = $CI->car_model->get_types(  );

    $types = array();
    foreach ($records as $type) 
    {
        $types[$type['id']] = $type['name'];
    }

    return $types;
}

function get_comforts(  )
{
    $CI = & get_instance();
    $CI->load->model('car_model');
    $records = $CI->car_model->get_comforts(  );

    $comforts = array();
    foreach ($records as $comfort) 
    {
        $comforts[$comfort['id']] = $comfort['name'];
    }

    return $comforts;
}

?>