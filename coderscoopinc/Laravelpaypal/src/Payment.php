<?php
// this model is a payment, it will be used by the transaction model 


namespace Coderscoopinc\Laravelpaypal;

class Payment
{



	protected $salesData;

	public function __construct(){
		$returnUrl ="";
		$cancelUrl ="";
		$amountToBePaid ="2";
		$this->salesData = '{
              "intent":"sale",
              "redirect_urls":{
                "return_url":"http://example.com/your_redirect_url.html",
                "cancel_url":"http://example.com/your_cancel_url.html"
              },
              "payer":{
                "payment_method":"paypal"
              },
              "transactions":[
                {
                  "amount":{
                    "total":"7.47",
                    "currency":"USD"
                  }
                }
              ]
            }';
	}

	
	
	public function data(){
		return $this->salesData;
	}
}



