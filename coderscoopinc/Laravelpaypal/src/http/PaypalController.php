<?php

namespace Coderscoopinc\Laravelpaypal\Http;

use App\Http\Controllers\Controller;
use Coderscoopinc\Laravelpaypal\Transaction;

class PaypalController extends Controller{

	public function index(){
		$headers =  json_encode(['headers' => [
						        'Accept' => 'application/json',
						        'Accept-Languge' => 'en_US']
						       ]);
		$transaction = new Transaction(env('PAYPAL_CLIENT_ID'), env('PAYPAL_CLIENT_SECRET'));
		return json_encode($transaction->createPayment());
	}
}
