<?php
class EndpointController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }
    
    public function createAction()
    {
    	if ($_GET['token'] != NULL) 
    	{   		
	    	// Create a new Amazon SNS client
	    	$sns = Aws\Sns\SnsClient::factory(array(
	    			'key'    => 'AKIAIABMO7HONKJPMUVQ',
	    			'secret' => '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',
	    			'region' => 'us-west-2'
	    	));
	    	
	    	// Get the platform application
	    	$Model1 = $sns->listPlatformApplications();
	    	
	    	// Get the Arn of the first application
	    	$AppArn = $Model1['PlatformApplications'][0]['PlatformApplicationArn'];
	    	
	    	// Get the application's endpoints
	    	$Model2 = $sns->listEndpointsByPlatformApplication(array('PlatformApplicationArn' => $AppArn));
	    	
	    	foreach ($Model2['Endpoints'] as $Endpoint) {
	    		$result = $sns->getEndpointAttributes(array(
	    				// EndpointArn is required
	    				'EndpointArn' => $Endpoint['EndpointArn']
	    		));
	    	}
	    	
	    	try {
	    		// Add new endpoint
	    		$new_ep = $sns->createPlatformEndpoint(array(
	    				// PlatformApplicationArn is required
	    				'PlatformApplicationArn' => $AppArn,
	    				// Token is required
	    				'Token' => $_GET['token'],
	    				'CustomUserData' => 'user_'.time('H:i:sT'),
	    				'Attributes' => array(
	    						// Associative array of custom 'String' key names
	    						'Enabled' => 'true'
	    				),
	    		));
	    	} catch(Exception $e) {
	    		echo 'Message: ' .$e->getMessage();
	    	}    	
    	}
    }


}

