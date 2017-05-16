<?php

include './src/Item.php';

use PHPUnit\Framework\TestCase;
use Coderscoopinc\Laravelpaypal\Item;

class itemTest extends PHPUnit_Framework_TestCase
{

	protected $item;



	public function setup(){
			$this->item = new Item("Thing1","This is thing 1","1","2","0.0","1","CAD");
	}


	

	/** @test */ 
	public function an_item_exsists(){
		$this->assertNotNull($this->item);
	}

		/** @test */ 
	public function a_cost_returns_correct_value(){
		$this->assertEquals($this->item->cost(),2);
	}

	


}