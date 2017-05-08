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

	protected $products;

	public function __construct($client_id,$client_secret){

		$this->client_id = $client_id;
		$this->client_secret = $client_secret;
		$this->accessKey = $this->getAccessKey();


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
