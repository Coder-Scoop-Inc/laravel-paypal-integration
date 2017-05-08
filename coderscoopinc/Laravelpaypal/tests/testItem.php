<?php

include './src/Item.php';

use PHPUnit\Framework\TestCase;
use \Coderscoopinc\Laravelpaypal\Item;

class itemTest extends PHPUnit_Framework_TestCase
{

	protected $item;

	public function setup(){	
			$this->item = new Item('test item');	
	}

	/** @test */ 
	public function an_item_exsists(){
		$this->assertTrue(null !== $this->item);
	}

}