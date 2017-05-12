<?php
// this model is a payment, it will be used by the transaction model 


namespace Coderscoopinc\Laravelpaypal;

use GuzzleHttp\Client;

class Payment
{



	protected $salesData;
  protected $paypalPaymentUrl = 'https://api.sandbox.paypal.com/v1/payments/payment';
  protected $accessKey;
 
  protected $client_id;
  protected $client_secret;

  protected $paymentBody;

  public function __construct($client_id,$client_secret){
		$returnUrl ="";
		$cancelUrl ="";
		$amountToBePaid ="2";
    $this->getAccessKey();

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

  public function accessKey(){
    return $this->accessKey;
  }
  
  public function paymentBody(){
    return $this->paymentBody();
  }

  public function data(){
    return $this->salesData;
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
      $this->accessKey = $responseBody->access_token;
    }catch (\Exception $ex) {
             $error = $ex->getMessage();
    }
  }


  public function createPaypalPayment(){
     try {
            $client = new Client();
            $paymentResponse = $client->request('POST', $this->paypalPaymentUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->accessKey,
                ],
                'body' => $this->salesData()
            ]);

            $this->paymentBody = json_decode($paymentResponse->getBody()->getContents());
        } catch (\Exception $ex) {
            $error =  json_encode($ex->getMessage());
            var_dump($error);
        }
  }

}



