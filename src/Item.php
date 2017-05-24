<?php
// this model is a payment, line items for a payment
//currently a place holder to remind me to refactor a payment to have many items


namespace Coderscoop\Laravelpaypal;
/**
 * Item classes are each line item in a sale,
 * 
 * @param name, description, quanitty, price, tax, sku and currency all as strings
 */
class Item
{

 	protected $name;
      protected $description;
      protected $quantity;
      protected $price;
      protected $tax;
      protected $sku;
      protected $currency;

      public function __construct($name, $description, $quantity , $price , $tax , $sku , $currency )
      {
	      $this->name = $name;
	      $this->description = $description;
	      $this->quantity = $quantity;
	      $this->price = $price;
	      $this->tax =$tax;
	      $this->sku = $sku;
	      $this->currency =$currency;

      }

      public function cost(){
       return (string)$this->price * (1 + $this->tax) * $this->quantity;
      }
      
      public function currency(){
            return $this->currency;
      }

      /**
       * returns a sting with item data, used to build salesData
       *
       * sales data can contain many of items
       * 
       * @return JSON object with payment info from paypal
       * 
       */

      public function toJson(){
            return json_encode(array("name" =>$this->name,
                                     "description" => $this->description,
                                     "quantity" =>$this->quantity,
                                     "price" => $this->price,
                                     "tax" =>$this->tax,
                                     "sku" =>$this->sku,
                                     "currency" =>$this->currency));
      }

   
}


