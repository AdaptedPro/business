<?php
class NewsController extends Zend_Controller_Action
{
	
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	$news_model = new Application_Model_NewsMapper();
    	$news = $news_model->get_all_news_items_from_db();
    	
    	$table_output = "";
    	foreach ($news as $item)
    	{
    		$table_output .= "<tr> \n";
    		$table_output .= "    <td>{$item['program_news_title']['S']}</td> \n";
    		$table_output .= "    <td><img src='{$item['program_news_image']['S']}' alt='Story image.' /></td> \n";
    		$table_output .= "    <td>{$item['program_news_details']['S']}</td> \n";
    		$table_output .= "</tr> \n";
    	}

    	$this->view->table_output = $table_output;
    }

    public function createAction()
    {
    	// action body
    } 
    
    public function ajaxcreateAction()
    {
    	$this->check_form_errors($_POST);
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
	    		echo 'News item saved and push notification sent!';	
	    	} else {
	    		echo 'Push notification was not sent!';
	    	}
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

