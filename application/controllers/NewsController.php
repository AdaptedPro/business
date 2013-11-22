<?php
class NewsController extends Zend_Controller_Action
{
	#vars declared for ajax manipulation.
	protected $error_array;
	protected $news_item_title;
	protected $news_item_summary;
	protected $news_item_details;	
	
    public function init()
    {
    	if (isset($_SESSION['timeout'])) {
    		#Set timeout for 30mins.
    		if ($_SESSION['timeout'] + 30 * 60 < time()) {
    			header( "Location: {$this->view->baseUrl()}?r=".urlencode(str_replace($this->view->baseUrl(), "", $_SERVER['REQUEST_URI'])) );
    		}
    	}
    }

    public function indexAction()
    {   
    	if (!isset($_SESSION['auth_user'])) {
    		header( "Location: {$this->view->baseUrl()}?r=".urlencode(str_replace($this->view->baseUrl(), "", $_SERVER['REQUEST_URI'])) );
    	} else {
    		#For adding content for page template layout.php file.
    		#$this->view->placeholder('aside_content')->set($this->get_aside_content());
    		$this->view->image_list		= $this->build_image_list();
    	    		
    		if (isset($_POST['submit'])) {
    			$this->error_array['title'];
    			$this->error_array['summary'];
    			$this->error_array['details'];
    			$this->error_array['image'];
    			$this->news_item_title		= $_POST ? $_POST['news_item_title'] : "";
    			$this->news_item_summary	= $_POST ? $_POST['news_item_summary'] : "";
    			$this->news_item_details	= $_POST ? $_POST['news_item_details'] : "";   
    			$this->news_item_public		= $_POST ? $_POST['news_item_is_public'] : "";
    			$this->view->main_content 	= $this->check_form_errors($_POST,false,$_FILES);
    		} else {
    			$this->check_form_errors(NULL,false,NULL);
    		}
    		
    	} 	    
    }

    public function listAction()
    {
    	if (!isset($_SESSION['auth_user'])) {
    		$this->view->placeholder('home_title')->set('SSS Program News');
    		$this->view->placeholder('home_link')->set($this->view->baseUrl().'/news/list');
    	}    	
    	$news_model = new Application_Model_NewsMapper();
    	$news = $news_model->get_all_news_items_from_db(); 
    	 	
		$output = "<ul> \n";
    	foreach ($news as $item)
    	{
    		if(!isset($item['error']['S'])) {
    			$prefix = "https://rccsss.s3-us-west-2.amazonaws.com/";
    			$image = $item['program_news_image']['S'];
    			 
    			if (strpos($image,$prefix) === false) {
    				$image = $prefix.$image;
    			}    			
    			
    			$output .= "<li> \n";
    			$output .= "	<div class='list_heading_holder'>
    								<a href='".$this->view->baseUrl()."/news/item/id/".$item['rcc_sss_program_news_data_id']['S']."'>{$item['program_news_title']['S']}</a>
    							</div> \n";
    			$output .= "	<div class='list_item_image'><img src='{$image}' alt='' /></div><br class='clr' /></li> \n";
    			$output .= "</li> \n";   	
    		}
    		
    	}
    	$output .= "</ul> \n";
    	$this->view->news_item_list = $output;
    }    
    
    public function itemAction()
    {
        if (!isset($_SESSION['auth_user'])) {
    		$this->view->placeholder('home_title')->set('SSS Program News');
    		$this->view->placeholder('home_link')->set($this->view->baseUrl().'/news/list');
    	}   
    	$id = $this->getParam('id');
    	if ($id == '' || $id != NULL) {
    		$news_model = new Application_Model_NewsMapper();
    		$news_item = $news_model->get_single_news_item_from_db($id);
    		foreach ($news_item as $key => $val) {
    	
    			$prefix = "https://rccsss.s3-us-west-2.amazonaws.com/";
    			$image = $val['program_news_image']['S'];
    	
    			if (strpos($image,$prefix) === false) {
    				$image = $prefix.$image;
    			}
    	
    			$this->view->news_item_title		= $val['program_news_title']['S'];
    			$this->view->news_item_image		= $image;
    			$this->view->news_item_summary		= $val['program_news_summary']['S'];
    			$this->view->news_item_details		= $val['program_news_details']['S'];
    			$this->view->news_item_public		= $val['public']['S'];
    			$this->view->news_item_id			= $id;
    			break;
    		}
    	}    	
    }
    
    public function showAction()
    {
    	if (!isset($_SESSION['auth_user'])) {
    		header( "Location: {$this->view->baseUrl()}?r=".urlencode(str_replace($this->view->baseUrl(), "", $_SERVER['REQUEST_URI'])) );
    	} else {    	
	    	
	    	$id = $this->getParam('id');
	    	if ($id == '' || $id != NULL) {    		
		    	$news_model = new Application_Model_NewsMapper();
		    	$news_item = $news_model->get_single_news_item_from_db($id);
		    	foreach ($news_item as $key => $val) {
		    		$image = 'https://rccsss.s3-us-west-2.amazonaws.com/'.$val['program_news_image']['S'];
		    		$this->view->news_item_title	= $val['program_news_title']['S'];
		    		$this->view->news_item_image	= $image;
		    		$this->view->news_item_summary	= $val['program_news_summary']['S'];
		    		$this->view->news_item_details	= $val['program_news_details']['S'];
		    		$this->news_item_public			= $val['public']['S'];
		    		$this->view->news_item_id = $id;
					break;
		    	}
	    	}
    	}
    } 

    public function editAction()
    {
    	if (!isset($_SESSION['auth_user'])) {
    		header( "Location: {$this->view->baseUrl()}?r=".urlencode(str_replace($this->view->baseUrl(), "", $_SERVER['REQUEST_URI'])) );
    	} else {
    		$id = $this->getParam('id');
    		if ($id == '' || $id != NULL) {
    			$news_model = new Application_Model_NewsMapper();
    			$news_item = $news_model->get_single_news_item_from_db($id);
    			foreach ($news_item as $key => $val) {
    				$image = 'https://rccsss.s3-us-west-2.amazonaws.com/'.$val['program_news_image']['S'];
    				$this->news_item_title		= $val['program_news_title']['S'];
    				$this->news_item_image		= $image;
    				$this->news_item_summary	= $val['program_news_summary']['S'];
    				$this->news_item_details	= $val['program_news_details']['S'];
    				$this->news_item_public		= $val['public']['S'];
    				$this->news_item_id			= $id;
    				break;
    			}
    			$this->image_list		= $this->build_image_list();
    			ob_start();
    			include_once 'application/views/scripts/news/includes/news_form.php';
    			$this->view->main_content = ob_get_contents();
    			ob_end_clean();    			
    			
    		}    		
    	}  
    }

    public function deleteAction()
    {
    	if (!isset($_SESSION['auth_user'])) {
    		header( "Location: {$this->view->baseUrl()}?r=".urlencode(str_replace($this->view->baseUrl(), "", $_SERVER['REQUEST_URI'])) );
    	} else {
    		$id = $this->getParam('id');
    		if ($id == '' || $id != NULL) {
    			$news_model = new Application_Model_NewsMapper();
    			$delete_item = $news_model->delete_news_item_in_db($id);
    		}
    		$this->_helper->layout()->disableLayout();
    		$this->_helper->viewRenderer->setNoRender(true);    		    		
    	}    	 
    }    
    
    public function ajaxcreateAction()
    {
    	$this->check_form_errors($_POST,true);
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);    	 	
    }
    
    public function ajaxaddAction()
    {
    	echo $this->check_form_errors(NULL,false,NULL);
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);    	
    }
    
    private function check_form_errors($DATA,$ajax,$file=null)
    {	
    	#If requesting a blank form do this.
    	if ($DATA == '' || $DATA == NULL) {
     		$this->image_list = $this->view->image_list;
     		ob_start();
     		include_once 'application/views/scripts/news/includes/news_form.php';
     		$this->view->main_content = ob_get_contents();
     		$add = ob_get_contents();
     		ob_end_clean(); 
     		return $add;
    	} else {

    		if ($DATA['news_item_title'] == '' || $DATA['news_item_title'] == NULL)
    		{
    			$this->error_array['title'] = '<span class="error_msg">Title can&rsquo;t be left blank.</span><br />';
    		}
    			
    		if ($DATA['news_item_summary'] == '' || $DATA['news_item_summary'] == NULL)
    		{
    			$this->error_array['summary'] = '<span class="error_msg">Summary can&rsquo;t be left blank.</span><br />';
    		}
    		
    		if ($DATA['news_item_details'] == '' || $DATA['news_item_details'] == NULL)
    		{
    			$this->error_array['details'] = '<span class="error_msg">Details can&rsquo;t be left blank.</span><br />';
    		}
    			
    		if (($file == "" || $file == NULL) &&
    				($DATA['news_item_lib_image'] == '' || $DATA['news_item_lib_image'] == NULL))
    		{
    			$this->error_array['image'] = '<span class="error_msg">You must select an image.</span><br />';
    		}   	

    		if (count($this->error_array) == 0)
    		{
    			if (!$ajax) {
    				//Upload image if not using ajax and and create image name.
    				date_default_timezone_set('America/Los_Angeles');
    				$image_model	= new Application_Model_ImagesMapper();
    				$image_name 	= date('Y-m-d_H-i-s').'_'. htmlentities($file["news_item_image"]["name"]);
    				$image_path		= $file["news_item_image"]["tmp_name"];
    		
    				//Pass created image name on for news insert.
    				$do_upload = $image_model->upload_image_to_bucket($image_name, $image_path);
    				$DATA['news_item_image'] = $do_upload;
    			}
    		
    			//Create item;
    			$news_model	= new Application_Model_NewsMapper();
    			$message	= $news_model->add_news_item_to_db($DATA);
				
    			$output = "";
    			if ( is_numeric($message->getPath('ConsumedCapacity/CapacityUnits')))
    			{
    				//Send Push notification
    				$this->sendPush($DATA['news_item_title']);
    				$output .= '<p>News item saved and push notification sent!</p>';
    			} else {
    				$output .= '<p>News item saved but the push notification was not sent!<br /></p>';
    			}
    			$output .= '<p><span id="add_link" class="btn_link">Add another item</span></p>';
				echo $output;
    		} else {
    			//Display errors
    			if ($ajax) {
    				include_once 'application/views/scripts/news/includes/news_form.php';
    			} else {
    				ob_start();
    				include_once 'application/views/scripts/news/includes/news_form.php';
    				$this->view->main_content = ob_get_contents();
    				ob_end_clean();    				
    			}
    		}    		
    	}	
    }
        
    private function sendPush($message) 
    {
    	$news_model = new Application_Model_NewsMapper();
    	$Model1 = $news_model->list_all_platform_applications();
    	 
    	// Get the Arn of the first application, because we only have 1 application
    	$AppArn = $Model1['PlatformApplications'][0]['PlatformApplicationArn'];
    	 
    	// Get the application's endpoints
    	$Model2 = $news_model->list_all_endpoints_by_platform_applications($AppArn);
    	
    	$sns = $news_model->sns_client();
    	 
    	foreach ($Model2['Endpoints'] as $Endpoint)
    	{
    		$EndpointArn = $Endpoint['EndpointArn'];
    	}
    	 
    	// Send a message to each endpoint
    	foreach ($Model2['Endpoints'] as $Endpoint) {
    		$EndpointArn = $Endpoint['EndpointArn'];
    		 
    		try {
    			$sns->publish(
    					array(
    							'Message' => $message,
    							'TargetArn' => $EndpointArn
    					)
    			);
    			 
    		} catch (Exception $e) {
    			print($EndpointArn . " - Failed: " . $e->getMessage() . "!\n");
    		}
    	}    	
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
    
    public function feedAction()
    {   	
    	require ('library/FeedGen/FeedWriter.php');
    	require ('library/FeedGen/FeedItem.php');

    	$news_model = new Application_Model_NewsMapper();
    	$news = $news_model->get_all_news_items_from_db();
    	
    	//Creating an instance of FeedWriter class.
    	//The constant ATOM is passed to mention the version
    	$ProgramNewsFeed = new FeedWriter(ATOM);
    	
    	//Setting the channel elements
    	//Use wrapper functions for common elements
    	$ProgramNewsFeed->setTitle('RCC SSS Program News');
    	$ProgramNewsFeed->setLink('');
    	
    	//For other channel elements, use setChannelElement() function
    	$ProgramNewsFeed->setChannelElement('updated', date(DATE_ATOM , time()));
    	$ProgramNewsFeed->setChannelElement('author', array('name'=>'Student Support Services'));
    	  	
    	// Each item will contain the attributes we added
    	foreach ($news as $item) 
    	{
    		//Create an empty FeedItem

    		$newItem = $ProgramNewsFeed->createNewItem();
    		if(!isset($item['error']['S'])) {
			
    			//Add elements to the feed item
    			//Use wrapper functions to add common feed elements
    			$newItem->setId($item['rcc_sss_program_news_data_id']['S']);
    			$newItem->setTitle($item['program_news_title']['S']);
    			$newItem->setLink($this->view->baseUrl()."/news/item/id/".$item['rcc_sss_program_news_data_id']['S']);
    			$newItem->setImage($item['program_news_image']['S']);
    			$newItem->setDate($item['created_on']['S']);
    			$newItem->setSummary( $item['program_news_summary']['S']);
    			$newItem->setDescription( $item['program_news_details']['S']);
    			 
    			//Now add the feed item
    			$ProgramNewsFeed->addItem($newItem);  
    		} 
    	}    	

    	//OK. Everything is done. Now genarate the feed.
    	$ProgramNewsFeed->genarateFeed();  

    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);    	
    }

}

