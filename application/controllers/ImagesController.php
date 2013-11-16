<?php

class ImagesController extends Zend_Controller_Action
{

    public function init()
    {
        if(!isset($_SESSION['auth_user'])) {
    		header( "Location: {$this->view->baseUrl()}?r=".urlencode(str_replace($this->view->baseUrl(), "", $_SERVER['REQUEST_URI'])) );
    	} else {
    		if (isset($_SESSION['timeout'])) {
    			if ($_SESSION['timeout'] + 30 * 60 < time()) {
    				header( "Location: {$this->view->baseUrl()}index/logout" );
    			}
    		}    		
    	}
    }

    public function indexAction()
    {

    }
    
    private function get_image_list()
    {
    	$image_model = new Application_Model_ImagesMapper();
    	$image_iterator = $image_model->get_all_images_from_bucket();
    	
    	$output = "";
    	foreach ($image_iterator as $object) {
    		$output.= "<img src='https://rccsss.s3-us-west-2.amazonaws.com/".$object['Key'] . "' /><br />\n";
    	}

    	return $output;   	
    }
    
    
    public function uploadAction()
    {
    	date_default_timezone_set('America/Los_Angeles');    	
    	$image_model	= new Application_Model_ImagesMapper();
    	$image_name 	= date('Y-m-d_H-i-s').'_'. htmlentities($_FILES["news_item_image"]["name"]);
    	$image_path		= $_FILES["news_item_image"]["tmp_name"];
    	
    	$do_upload = $image_model->upload_image_to_bucket($image_name, $image_path);  	

    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);    		
    }


}

