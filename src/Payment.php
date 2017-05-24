<?php
// this model is a payment, it will be used by the transaction model 


namespace Coderscoop\Laravelpaypal;

use GuzzleHttp\Client;
/**
 * Payment object interacts with Payment API to open, approve and confirm payment
 *
 * Additionally this objet can request payment info from paypal.  As long as you store 
 * 
 * 
 * 
 */

class Payment
{



	protected $salesData = null;
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

  /**
   * payment constructor, 
   *
   * client_id and client_secret are from paypal and must be stored in your projects .env file * for security 
   * reasons, salesData should be past from controller, if it is not, then it needs to be added via
   * addItem method
   * 
   */

  public function __construct($client_id,$client_secret,$salesData = null){
		

    $this->client_id = $client_id;
    $this->client_secret = $client_secret;
    $this->getAccessKey();

		$this->salesData = $salesData;
           
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
  
  /**
   * uses cleint id and client secret to get access token from paypal
   *
   * 
   * 
   * @return sets object access token, is needed for all API transactions
   * 
   */
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

/**
 * creates paypal payment object 
 *
 * this method creats the paypal payment object
 * 

 * @return if successfuo JSON object with payment id, a unique id for use with paypal REST API
 *        if failuer :string with error details
 * 
 */
  public function createPaypalPayment(){
     try {
            $client = new Client();
            $paymentResponse = $client->request('POST', $this->paypalPaymentUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->accessKey,
                ],
                'body' => $this->salesData->salesData()
            ]);
           $this->parsePaymentBody($paymentResponse);
       
        } catch (\Exception $ex) {
            $error =  $ex->getMessage();
            return $error;
        }
             return $this->paymentBody->id;

  }

/**
 * utiliy method, sets object properties based on returned  paypal info
 * 
 * 
 */

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
  /**
   *gets payment info from paypal
   *
   * can be used at anytime after a payment has been created. 
   * 
   * @param payment Id
   * @return JSON object with current payment info and status.
   * 
   */
  public function paymentInfo($id){
      $client = new Client();
      try{
        $paymentInfo = $client->request('GET', "https://api.sandbox.paypal.com/v1/payments/payment/" . $id , [
                  'headers' => [
                      'Authorization' => 'Bearer ' . $this->accessKey,
                      'Content-Type' => 'application/json',
                  ]
                  
              ]);
      }
      catch(\Exception $ex) {
         $error =  json_encode($ex->getMessage());
        return($error);
      }
     $this->parsePaymentBody($paymentInfo);
     return $this->paymentBody;
  } 

   /**
   *excutes a payment
   *
   * call this after a user has approved a payment (you can check this in info) to finalize the sale
   * 
   * @return JSON object with current payment info and status.  Will fail if the payment has not been apprved 
   * yet
   * 
   */

  public function execute(){

    $body = '{"payer_id":"' . $this->payer_id . '"}';
    $client = new Client();
    try{
      $paymentInfo = $client->request('POST', $this->execute_url, ['headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->accessKey,
                ],
                'body' => $body
            ]);
    }
    catch (\Exception $ex) {
      $error =  json_encode($ex->getMessage());
      return($error);
    }
   $this->parsePaymentBody($paymentInfo);
     return $this->paymentBody;

  }

}


