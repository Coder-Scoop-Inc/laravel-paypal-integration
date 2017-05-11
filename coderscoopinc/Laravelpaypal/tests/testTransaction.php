<?php

include './src/Transaction.php';
//include './src/Payment.php';

use PHPUnit\Framework\TestCase;
use \Coderscoopinc\Laravelpaypal\Transaction;
use \Coderscoopinc\Laravelpaypal\Payment;


class TransactionTest extends PHPUnit_Framework_TestCase
{

		protected $transaction;
		protected $sandbox_client_id;
		protected $sandbox_client_secret;
		protected $payment;

		public function setup(){
		
			//only use sand box keys here !!!!  Actual keys for production should be stored in .env
			$this->sandbox_client_id = "AYv2bG4v0XqJ99TTQV8EJzZW5ijpvauxeOhk8qjQ62abxPaq8-quxrtaVHXiCLHLk-afSshWkmRogVWD";
			$this->sandbox_client_secret = "EN5l7_20o2Ug5jQp2dr3Xa7EMttYiU-g4v1vF-Ld1ybDaS1SPCJ2BWKLWBw-6tFjJ3A81euLHK0zYJcr";

			$this->transaction = new Transaction($this->sandbox_client_id,$this->sandbox_client_secret);
		//	$this->payment = new Payment();

			//$this->transaction->payment($this->payment);
		}

		/** @test */ 
		public function a_transaction_had_a_payment_object(){
			$this->assertNotNull($this->transaction->payment());
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
		
		
}