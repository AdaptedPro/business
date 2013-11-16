<?php
class Application_Model_NewsMapper {
	
	protected $dbClient;
	protected $snsClient;
	
	public function __construct($dbClient=null,$snsClient=null)
	{
		// Create a new Amazon DynamoDbClient
		$this->dbClient = Aws\DynamoDb\DynamoDbClient::factory(array(
				'key'    => 'AKIAIABMO7HONKJPMUVQ',
				'secret' => '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',
				'region' => 'us-west-2'
		));		
		
		// Create a new Amazon SnsClient
		$this->snsClient = Aws\Sns\SnsClient::factory(array(
				'key'    => 'AKIAIABMO7HONKJPMUVQ',
				'secret' => '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',
				'region' => 'us-west-2'
		));		
	}
	
	public function sns_client()
	{
		return $this->snsClient;
	}
	
	public function list_all_platform_applications()
	{
		return $this->snsClient->listPlatformApplications();
	}
	
	public function list_all_endpoints_by_platform_applications($AppArn)
	{
		return $this->snsClient->listEndpointsByPlatformApplication(array('PlatformApplicationArn' => $AppArn));
	}	
	
	public function count_all_news_items_in_db()
	{
	
	}

	public function get_all_news_items_from_db()
	{	 
		//Iterate Query
		$iterator = $this->dbClient->getIterator('Query', array(
				'TableName'     => 'rcc_sss_program_news_data',
				'KeyConditions' => array(
						'rcc_sss_program_news_data_type' => array(
								'AttributeValueList' => array(
										array('S' => 'news')
								),
								'ComparisonOperator' => 'EQ'
						),
				)
		));

		return $iterator;
	}	
	
	public function add_news_item_to_db($DATA)
	{
		//This will add item into NoSQL DB
		if ( isset($DATA['news_item_image']) ) {
			$image = $DATA['news_item_image'];
		}
		
		if ( isset($DATA['news_item_lib_image'])) {
			$image = $DATA['news_item_lib_image'];
		}
				
		date_default_timezone_set('America/Los_Angeles');
		$result = $this->dbClient->putItem(array(
				'TableName' => 'rcc_sss_program_news_data',
				'Item' => $this->dbClient->formatAttributes(array(
						'rcc_sss_program_news_data_type'	=> 'news',
						'rcc_sss_program_news_data_id'		=> 8, #Unique identifier
						'created_on'						=> date('Y-m-d H:i:s'),
						'program_news_title'				=> $DATA['news_item_title'],
						'program_news_summary'				=> $DATA['news_item_summary'],
						'program_news_details'				=> $DATA['news_item_details'],
						'program_news_image'				=> $image ? $image : 'https://scontent-a-pao.xx.fbcdn.net/hphotos-ash3/p480x480/1170703_541495202570684_2024681942_n.jpg',
						'public'							=> $DATA['news_item_is_public']
				)),
				'ReturnConsumedCapacity' => 'TOTAL'
		));

		return $result;
	}
	
	public function update_news_item_in_db()
	{
	
	}

	public function delete_news_item_in_db()
	{
	
	}	
}