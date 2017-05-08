<?php
// this model is a payment, it will be used by the transaction model 


namespace Coderscoopinc\Laravelpaypal;

class Payment
{



	protected $salesData;

	public function __construct(){
		$this->salesData = 
	'{
		"intent":"sale",
 	 "redirect_urls":{
	  "return_url":"return_url",
	  "cancel_url": "cancel_url"
	 },
 	 "payer": {
 		 	"payment_method":"paypal"
   },
   "transactions":[{
      "amount":{
          "total":"15.00",
          "currency":"USD"
          },
           "description": "TEST DESCRIPTION”                    }
  	]
	}';

	}

	public function salesData(){
		return $this->salesData;
	}
}
