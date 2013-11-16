<?php
class NewsController extends Zend_Controller_Action
{
	#Var declared for ajax manipulation.
	protected $error_array;
	protected $news_item_title;
	protected $news_item_summary;
	protected $news_item_details;	
	
    public function init()
    {
    	if (isset($_SESSION['timeout'])) {
    		if ($_SESSION['timeout'] + 30 * 60 < time()) {
    			header( "Location: {$this->view->baseUrl()}?r=".urlencode(str_replace($this->view->baseUrl(), "", $_SERVER['REQUEST_URI'])) );

    		}
    	}
    }

    public function indexAction()
    {   
    	if(!isset($_SESSION['auth_user'])) {
    		header( "Location: {$this->view->baseUrl()}?r=".urlencode(str_replace($this->view->baseUrl(), "", $_SERVER['REQUEST_URI'])) );
    	} 	
    	$this->view->error_array['title'];
    	$this->view->error_array['summary'];
    	$this->view->error_array['details'];
    	//$this->view->error_array['image'];    	
    	$this->view->news_item_title	= $_POST ? $_POST['news_item_title'] : "";
    	$this->view->news_item_summary	= $_POST ? $_POST['news_item_summary'] : "";
    	$this->view->news_item_details	= $_POST ? $_POST['news_item_details'] : "";
    	//$this->view->image_select		= $this->build_image_select();
    	 
    	//$this->view->placeholder('aside_content')->set($this->get_aside_content());
    }
    
    public function ajaxcreateAction()
    {
    	$this->check_form_errors($_POST);
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);    	 	
    }
    
    public function ajaxaddAction()
    {
    	$this->error_array['title'];
    	$this->error_array['summary'];
    	$this->error_array['details'];
    	$this->news_item_title;
    	$this->news_item_summary;
    	$this->news_item_details;   	
    	include_once 'application/views/scripts/news/includes/news_form.php';
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);    	
    }
    
    private function check_form_errors($DATA)
    {
 		$error_array = array();
 		
 		if ($DATA['news_item_title'] == '' || $DATA['news_item_title'] == NULL) 
 		{
 			$error_array['title'] = '<span class="error_msg">Title can&rsquo;t be left blank.</span><br />';
 		}
 		
 		if ($DATA['news_item_summary'] == '' || $DATA['news_item_summary'] == NULL)
 		{
 			$error_array['summary'] = '<span class="error_msg">Summary can&rsquo;t be left blank.</span><br />';
 		} 	

 		if ($DATA['news_item_details'] == '' || $DATA['news_item_details'] == NULL)
 		{
 			$error_array['details'] = '<span class="error_msg">Details can&rsquo;t be left blank.</span><br />';
 		}

 		if (count($error_array) == 0) 
 		{
 			//Create item; 		    
 			$news_model	= new Application_Model_NewsMapper();
	    	$message	= $news_model->add_news_item_to_db($DATA);
	    	
	    	if ( is_numeric($message->getPath('ConsumedCapacity/CapacityUnits')))
	    	{
	    		//Send Push notification
	    		$this->sendPush($DATA['news_item_title']);
	    		$output = '<p>News item saved and push notification sent!</p>';
	    		$output .= '<p><span id="add_link" class="btn_link">Add another item</span></p>';
	    	} else {
	    		$output = '<p>News item saved but the push notification was not sent!<br /></p>';
	    		$output .= '<p><span id="add_link" class="btn_link">Add another item</span></p>';
	    	}
	    	echo $output; 
 		} else {
 			//Display errors
 			include_once 'application/views/scripts/news/includes/news_form.php';
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
    
    private function build_image_select()
    {
    	$image_model	= new Application_Model_ImagesMapper();
    	$image_iterator	= $image_model->get_all_images_from_bucket();
		$allowale_types	= array('bmp','gif','jpeg','jpg','png');
    	$output = "<ul> \n";
    	foreach ($image_iterator as $object) {
    		$image_name = explode(".", $object['Key']);
    		if (in_array($image_name[1],$allowale_types)) {
    			$output.= "\r <li><img src='https://rccsss.s3-us-west-2.amazonaws.com/".$object['Key']."' /></li>\n";
    		}
    	}	
    	$output .= "</ul> \n";
    	return $output;  
    }
    
    public function feedAction()
    {
    	date_default_timezone_set('America/Los_Angeles');    	
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
    	$ProgramNewsFeed->setLink('http://www.ajaxray.com/rss2/channel/about');
    	
    	//For other channel elements, use setChannelElement() function
    	$ProgramNewsFeed->setChannelElement('updated', date(DATE_ATOM , time()));
    	$ProgramNewsFeed->setChannelElement('author', array('name'=>'Student Support Services'));
    	  	
    	// Each item will contain the attributes we added
    	foreach ($news as $item) 
    	{
    		//Create an empty FeedItem
    		$newItem = $ProgramNewsFeed->createNewItem();
    		if($item['error']['S'] == NULL) {
				
    			//Add elements to the feed item
    			//Use wrapper functions to add common feed elements
    			$newItem->setTitle($item['program_news_title']['S']);
    			$newItem->setLink($item['program_news_image']['S']);
    			$newItem->setImage($item['program_news_image']['S']);
    			$newItem->setDate(time());
    			//Internally changed to "summary" tag for ATOM feed
    			$newItem->setDescription( $item['program_news_details']['S']);
    			 
    			//Now add the feed item
    			$ProgramNewsFeed->addItem($newItem);    			

    		} else {

    		}
    	}    	

    	//OK. Everything is done. Now genarate the feed.
    	$ProgramNewsFeed->genarateFeed();  

    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);    	
    }

}

