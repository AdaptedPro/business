<?php

class ImagesController extends Zend_Controller_Action
{
	protected $auth_session;
	
    public function init()
    {
    	$this->auth_session_data = new Zend_Session_Namespace('auth_session_data');    	
    	if(!isset($_SESSION['auth_session_data'])) {
    		header( "Location: {$this->view->baseUrl()}?r=".urlencode(str_replace($this->view->baseUrl(), "", $_SERVER['REQUEST_URI'])) );
    	} else {
    	    if (isset($_SESSION['auth_session_data']['timeout'])) {
    			#Set timeout for 30mins.
    			if ($_SESSION['auth_session_data']['timeout'] + 30 * 60 < time()) {
    				header( "Location: {$this->view->baseUrl()}?r=".urlencode(str_replace($this->view->baseUrl(), "", $_SERVER['REQUEST_URI'])) );
    			}
    		}   		
    	}
    }

    public function indexAction()
    {
    	$msg = "";
    	if (isset($_SESSION['upload'])) {    		
	    	switch ($_SESSION['upload']) {
	    		case 'Yes':
	    			$msg = '<p>Image was uploaded successfully.</p>';
	    			break;
	    		case 'No':
	    			$msg = '<p><span class="error_msg">Image could not be uploaded at this time.</span></p>';
	    			break;
	    		case '':
	    		case NULL:
	    			$msg = "";
	    			break;		
	    	}
	    	unset($_SESSION['upload']);
    	}
    	$this->view->image_list		= $this->build_image_list();
    	$this->view->upload_message	= $msg;
    }
    
    public function imagesAction()
    {
    	$image_model = new Application_Model_ImagesMapper();
    	#$image_name = '2013-11-15_21-46-46_2012-08-16T215305Z_814177836_GM1E88H0G5Y01_RTRMADP_3_SAFRICA-LONMIN.JPG';
    	#$remove_image = $image_model->delete_images_from_bucket($image_name);
    }
    
    public function deleteAction()
    {
    	$image_model = new Application_Model_ImagesMapper();
    	$image_name = str_replace('https://rccsss.s3-us-west-2.amazonaws.com/', '', $_POST["news_item_lib_image"]);
    	$remove_image = $image_model->delete_images_from_bucket($image_name);
		echo $this->build_image_list();
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);    	
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
		
    	if ($do_upload) {
    		$_SESSION['upload'] = 'Yes';
    	} else {
    		$_SESSION['upload'] = 'No';
    	}
    	
    	header( "Location: {$this->view->baseUrl()}/images");
    	
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);    		
    }
    

    private function build_image_list()
    {
    	$image_model	= new Application_Model_ImagesMapper();
    	$image_iterator	= $image_model->get_all_images_from_bucket();
    	$allowale_types	= array('bmp','gif','jpeg','jpg','png');
    	$output = "\r    <ul> \n";
    	foreach ($image_iterator as $object) {
    		$image_name = explode(".", $object['Key']);
    		if (in_array($image_name[1],$allowale_types)) {
    			$output.= "\r <li><img src='https://rccsss.s3-us-west-2.amazonaws.com/".$object['Key']."' /></li>\n";
    		}
    	}
    	$output .= "\r    </ul> \n";
    	return $output;
    }    


}

