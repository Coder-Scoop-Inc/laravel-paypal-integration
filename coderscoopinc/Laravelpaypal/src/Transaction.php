<?php
// this model is going to represent a transaction, from getting the key through to the end of the process
//  data and methods needed for this can be written here

namespace Coderscoopinc\Laravelpaypal;
use GuzzleHttp\Client;


class Transaction 
{

	protected $accessKey;

	protected $client_id;
	protected $client_secret;

	protected $client;
	protected $payment;

	protected $paypalPaymentUrl = 'https://api.sandbox.paypal.com/v1/payments/payment';

	public function __construct($client_id,$client_secret){

		$this->client_id = $client_id;
		$this->client_secret = $client_secret;
		$this->accessKey = $this->getAccessKey();
		$this->payment = new Payment;

	}

	public function payment($payment){
		$this->payment = $payment;
	}


	private function getAccessKey(){
		try{
			$client = new Client();


			$uri = 'https://' . $this->client_id . ':' . $this->client_secret . '@'.'api.sandbox.paypal.com/v1/oauth2/token';

			$res = $client->request('POST', $uri,
																['Accept' => 'application/json',
                									'Accept-Language' => 'en_US',
													   		 'form_params' => [
        																						'grant_type' => 'client_credentials',
																									],
																]);
			$responseBody = json_decode($res->getBody()->getContents());
		}catch (\Exception $ex) {
             $error = $ex->getMessage();
    }
    return isset($error) ? false:$responseBody->access_token;

	}

	public function createPayment(){
		 try {
            $client = new Client();
            $paymentResponse = $client->request('POST', $this->paypalPaymentUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->accessKey,
                ],
                'body' => $this->payment->data()
            ]);

            $paymentBody = json_decode($paymentResponse->getBody()->getContents());
            var_dump($paymentBody);
        } catch (\Exception $ex) {
            $error =  json_encode($ex->getMessage());
            var_dump($error);
        }
	}

	public function accessKeyHeaders(){
		return $this->accessKeyHeaders;
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

	public function accessKey(){
		return $this->accessKey;
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
