<?php
// this model is a payment, it will be used by the transaction model 


namespace Coderscoopinc\Laravelpaypal;

use GuzzleHttp\Client;

class Payment
{



	protected $salesData;
  protected $paypalPaymentUrl = 'https://api.sandbox.paypal.com/v1/payments/payment';
  protected $accessKey;

  protected $approval_url = null;
  protected $execute_url = null;
  protected $self_url = null;

  //PAY- id of the payment from paypal
  protected $paypal_id = null;

  //payer_id is the id returned from paypal identifying the payer/method paying
  protected $payer_id = null;

  //you need ot provide a return 
  protected $return_url = null;
 
  protected $client_id = null;
  protected $client_secret= null;

  protected $paymentBody;

 

  public function __construct($client_id,$client_secret,$return_url = null){
		

    $this->return_url=$return_url;

    $this->client_id = $client_id;
    $this->client_secret = $client_secret;
    $this->getAccessKey();

		$this->salesData = 
            '{
              "intent":"sale",
              "redirect_urls":{
                "return_url":"' . $return_url . '",
                "cancel_url":"http://example.com/your_cancel_url.html"
              },
              "payer":{
                "payment_method":"paypal"
              },
              "transactions":[
                {
                  "amount":{
                    "total":"18",
                    "currency":"USD"
                  },
              "item_list": {
                "items": [
                  {
                  "name": "hat",
                  "description": "Brown hat.",
                  "quantity": "1",
                  "price": "3",
                  "tax": "0.0",
                  "sku": "1",
                  "currency": "USD"
                  },
                  {
                  "name": "handbag",
                  "description": "Black handbag.",
                  "quantity": "1",
                  "price": "15",
                  "tax": "0.0",
                  "sku": "product34",
                  "currency": "USD"
                  }
                ]
                
                }
                }
              ]
            }';
          }

  public function accessKey(){
    return $this->accessKey;
  }

  public function returnUrl(){
    return $this->returnUrl;
  }

  public function approval_url(){
    return $this->approval_url;
  }

  public function paymentBody(){
    return $this->paymentBody();
  }

  public function salesData(){
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
                'body' => $this->salesData
            ]);

            $this->paymentBody = json_decode($paymentResponse->getBody()->getContents());
            $this->paypal_id = $this->paymentBody->id;
            //get the paypal process links from the response
            foreach ($this->paymentBody->links as $link){
              //this is the link for the payer top approve the payment
              if ($link->rel == "approval_url"){
                $this->approval_url = $link->href;
              }
              if ($link->rel == "execute"){
                $this->execute_url = $link->href;
              }
              if ($link->rel == "self"){
                $this->self_url = $link->href;
              }
            }
       
        } catch (\Exception $ex) {
            $error =  json_encode($ex->getMessage());
            var_dump($error);
        }
             return $this->paymentBody->id;

  }

  private function parsePaymentBody($paymentResponse){
     $this->paymentBody = json_decode($paymentResponse->getBody()->getContents());
           
            $this->paypal_id = $this->paymentBody->id;
            //get the paypal process links from the response
            foreach ($this->paymentBody->links as $link){
              //this is the link for the payer top approve the payment
              if ($link->rel == "approval_url"){
                $this->approval_url = $link->href;
              }
              if ($link->rel == "execute"){
                $this->execute_url = $link->href;
              }
              if ($link->rel == "self"){
                $this->self_url = $link->href;
              }

            }
            if (isset($this->paymentBody->payer->payer_info->payer_id)){
              $this->payer_id = $this->paymentBody->payer->payer_info->payer_id;
            }
       
  }

  public function paymentInfo($id){
      $client = new Client();
      $paymentInfo = $client->request('GET', "https://api.sandbox.paypal.com/v1/payments/payment/" . $id , [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessKey,
                    'Content-Type' => 'application/json',
                ]
                
            ]);
    $this->parsePaymentBody($paymentInfo);
      return $this->paymentBody;
  } 

  public function execute(){

    $body = '{"payer_id":"' . $this->payer_id . '"}';
    $client = new Client();
    try{
      $exePayment = $client->request('POST', $this->execute_url, ['headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->accessKey,
                ],
                'body' => $body
            ]);
    }
    catch (\Exception $ex) {
        $error =  json_encode($ex->getMessage());
        var_dump($error);
    }
    return $exePayment->getBody();

  }

}


// curl -v https://api.sandbox.paypal.com/v1/payments/payment/PAY-3GB39755DK182031DLELBN7Q/execute/ \\
//   -H "Content-Type:application/json" \\
//   -H "Authorization: Bearer Access-Token" \\
//   -d '{
//   "payer_id": "payer_id"
// }'

