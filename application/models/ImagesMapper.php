<?php

use Aws\S3\Exception\S3Exception;
#For uploads into the S3 bucket
use Guzzle\Http\EntityBody;

class Application_Model_ImagesMapper {
	
	protected $dbClient;	
	protected $s3Client;
	
	public function __construct($dbClient=null,$s3Client=null)
	{
		// Create a new Amazon DynamoDbClient
		$this->dbClient = Aws\DynamoDb\DynamoDbClient::factory(array(
				'key'    => 'AKIAIABMO7HONKJPMUVQ',
				'secret' => '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',
				'region' => 'us-west-2'
		));
	
		// Create a new Amazon SnsClient
		$this->s3Client = Aws\S3\S3Client::factory(array(
				'key'    => 'AKIAIABMO7HONKJPMUVQ',
				'secret' => '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',
				'region' => 'us-west-2'
		));
		
	}
	
	public function get_all_images_from_bucket()
	{	
		$iterator = $this->s3Client->getIterator('ListObjects', array(
				'Bucket' => 'rccsss',
		));
		return $iterator;		
	}
	
	public function upload_image_to_bucket($key,$path)
	{	
		$result = $this->s3Client->upload(
						'rccsss', 
						$key, 
						EntityBody::factory(fopen($path, 'r+')), 
						'public-read-write', 
						array('ContentType' => 'images/jpg') 
					);
		
		return $result["ObjectURL"];
	}	

	public function delete_images_from_bucket($key)
	{
		$result = $this->s3Client->deleteObject(array(
				'Bucket' => 'rccsss',
				'Key' => $key));
	}
}