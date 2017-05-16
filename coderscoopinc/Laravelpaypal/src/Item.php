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

      public function __construct($name, $description, $quantity , $price , $tax , $sku , $currency )
      {
	      $this->name =$name;
	      $this->description = $description;
	      $this->quantity = $quantity;
	      $this->price = $price;
	      $this->tax =$tax;
	      $this->sku = $sku;
	      $this->currency =$currency;

      }

   

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


