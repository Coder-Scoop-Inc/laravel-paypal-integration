<?php
// this model is going to represent a transaction, from getting the key through to the end of the process
//  data and methods needed for this can be written here

namespace Coderscoopinc\Laravelpaypal;

use GuzzleHttp\Client;
use \Coderscoopinc\Laravelpaypal\Payment;


class Transaction 
{


	
	protected $client;
	protected $payment = null;


	public function __construct($client_id,$client_secret){
		$this->payment = new Payment($this->client_id, $this->client_secret);

	}

	

	public function payment(){
		return $this->payment;
	}
	

	public function client_id(){
		return $this->client_id;
	}

	public function client_secret(){
		return $this->client_secret;
	}

	public function client(){
		return $this->client;
	}


    
}


// curl -v https://api.sandbox.paypal.com/v1/payments/payment \
//   -H "Content-Type: application/json" \
//   -H "Authorization: Bearer A21AAHlaWxVgMB5bB3nwYFtchIMFVGXOPYGuZza_u9FZ6rRIkp36ztnZCSUulONLR5kR-Ao-4l97ChClLu4LqupckEKwKPp6g-Token" \
//   -d '{
//   "intent":"sale",
//   "redirect_urls":{
//     "return_url":"http://example.com/your_redirect_url.html",
//     "cancel_url":"http://example.com/your_cancel_url.html"
//   },
//   "payer":{
//     "payment_method":"paypal"
//   },
//   "transactions":[
//     {
//       "amount":{
//         "total":"7.47",
//         "currency":"USD"
//       }
//     }
//   ]
// }'
