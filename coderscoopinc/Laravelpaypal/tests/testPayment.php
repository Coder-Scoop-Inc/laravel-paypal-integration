<?php

include './src/Payment.php';

use PHPUnit\Framework\TestCase;
use Coderscoopinc\Laravelpaypal\Payment;

class paymentTest extends PHPUnit_Framework_TestCase
{

	protected $payment;



	public function setup(){
			$this->sandbox_client_id = "AYv2bG4v0XqJ99TTQV8EJzZW5ijpvauxeOhk8qjQ62abxPaq8-quxrtaVHXiCLHLk-afSshWkmRogVWD";
			$this->sandbox_client_secret = "EN5l7_20o2Ug5jQp2dr3Xa7EMttYiU-g4v1vF-Ld1ybDaS1SPCJ2BWKLWBw-6tFjJ3A81euLHK0zYJcr";

			$this->payment = new Payment($this->sandbox_client_id,$this->sandbox_client_secret);
	}


		/** @test */ 
    public function a_payment_has_an_access_token()
    {
	       $this->assertNotFalse($this->payment->accessKey());
    }

	/** @test */ 
	public function a_payment_exsists(){
		$this->assertNotNull($this->payment);
	}

	/** @test */ 
	public function a_payment_has_sales_data(){
		$this->assertTrue(is_string($this->payment->salesData()));
	}

	public function a_payment_can_create_a_paypal_payment(){
			$this->payment->createPaypalPayment();

			$this->assertNotNull($this->transaction->paymentBody());
		}


}