<?php
class Application_Model_UsersMapper {

	protected $dbClient;

	public function __construct($dbClient=null)
	{
		// Create a new Amazon DynamoDbClient
		$this->dbClient = Aws\DynamoDb\DynamoDbClient::factory(array(
				'key'    => 'AKIAIABMO7HONKJPMUVQ',
				'secret' => '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',
				'region' => 'us-west-2'
		));
	}
	
	public function authenticate_user($DATA)
	{
		$result = $this->dbClient->getItem(array(
				// TableName is required
				'TableName' => 'rcc_sss_program_news_auth_users',
				// Key is required
				'Key' => array(
						// Associative array of custom 'AttributeName' key names
						'user_key' => array(
								'S' => 'admin',
						)
				),
				'AttributesToGet' => array('username','password'),
				'ConsistentRead' => true,
				'ReturnConsumedCapacity' => 'NONE',
		));		
				
		foreach ($result as $item)
		{
			$is_valid = "";
			if( $item['username']['S'] == trim($DATA['username']) && $item['password']['S'] == trim($DATA['password']) ) {
			    $is_valid = true;
			} else {
				$is_valid = false;				
			}
			
			return $is_valid;
		}
	}	
	
}