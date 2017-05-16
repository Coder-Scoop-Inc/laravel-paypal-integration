<?php

include './src/SalesData.php';

use PHPUnit\Framework\TestCase;
use \Coderscoopinc\Laravelpaypal\SalesData;

class salesDataTest extends PHPUnit_Framework_TestCase
{

	protected $salesData;

	public function setup(){	
			$this->salesData = new SalesData();	
	}

	/** @test */ 
	public function a_salesData_exsists(){
		$this->assertTrue(null !== $this->salesData);
	}

}