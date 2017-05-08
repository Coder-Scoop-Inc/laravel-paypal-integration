<?php
// this model is a payment, line items for a payment
//currently a place holder to remind me to refactor a payment to have many items


namespace Coderscoopinc\Laravelpaypal;

class Item
{

 			protected $name;
      protected $description;
      protected $quantity;
      protected $price;
      protected $tax;
      protected $sku;
      protected $currency;

      public function __construct($name, $description = "Decription Needed", $quantity = 1, $price =0, $tax = 0, $sku = 0, $currency = "USD")
      {
	      $this->$name =$name;
	      $this->$description = $description;
	      $this->$quantity = $quantity;
	      $this->$price = $price;
	      $this->$tax =$tax;
	      $this->$sku = $sku;
	      $this->$currency =$sku;

      }

   
}
