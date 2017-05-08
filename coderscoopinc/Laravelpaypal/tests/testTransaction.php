<?php

include './src/Transaction.php';

use PHPUnit\Framework\TestCase;
use \Coderscoopinc\Laravelpaypal\Transaction;

class TransactionTest extends PHPUnit_Framework_TestCase
{

		protected $transaction;
		protected $sandbox_client_id;
		protected $sandbox_client_secret;

		public function setup(){
		
			//only use sand box keys here !!!!  Actual keys for production should be stored in .env
			$this->sandbox_client_id = "AYv2bG4v0XqJ99TTQV8EJzZW5ijpvauxeOhk8qjQ62abxPaq8-quxrtaVHXiCLHLk-afSshWkmRogVWD";
			$this->sandbox_client_secret = "EN5l7_20o2Ug5jQp2dr3Xa7EMttYiU-g4v1vF-Ld1ybDaS1SPCJ2BWKLWBw-6tFjJ3A81euLHK0zYJcr";

			$this->transaction = new Transaction($this->sandbox_client_id,$this->sandbox_client_secret);
		}

				/** @test */ 
		public function a_transaction_has_a_client_secret(){
			$this->assertTrue(is_string($this->transaction->client_secret()));
		}

		/** @test */ 
		public function a_transaction_has_a_guzzle_client()
    {
	       !($this->assertNull($this->transaction->client()));
    }
	
		/** @test */ 
    public function a_transaction_has_an_access_token()
    {
	       $this->assertNotFalse($this->transaction->accessKey());
    }
		
		/** @test */ 
    public function a_transactoin_has_an_array_of_products(){

    }
}