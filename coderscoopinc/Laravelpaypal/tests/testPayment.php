<?php

include './src/Payment.php';

use PHPUnit\Framework\TestCase;
use \Coderscoopinc\Laravelpaypal\Payment;

class paymentTest extends PHPUnit_Framework_TestCase
{

	protected $payment;

	public function setup(){
		$this->payment = new Payment;
	}

	/** @test */ 
	public function a_payment_exsists(){
		$this->assertNotNull($this->payment);
	}

	/** @test */ 
	public function a_payment_has_sales_data(){
		$this->assertTrue(is_string($this->payment->salesData()));
	}


}