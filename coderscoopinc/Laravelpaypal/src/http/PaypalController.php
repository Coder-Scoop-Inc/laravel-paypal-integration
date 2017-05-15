<?php

namespace Coderscoopinc\Laravelpaypal\Http;


use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Coderscoopinc\Laravelpaypal\Payment;

class PaypalController extends Controller{


	public function index(){
		$return_url = "example.com";
		$headers =  json_encode(['headers' => [
						        'Accept' => 'application/json',
						        'Accept-Languge' => 'en_US']
						       ]);
		$payment = new Payment(env('PAYPAL_CLIENT_ID'), env('PAYPAL_CLIENT_SECRET'),$return_url);
		$payment->createPaypalPayment();
		//return redirect($payment->approval_url());
;
	}

	public function confirmpayment($id){
		$payment = new Payment(env('PAYPAL_CLIENT_ID'), env('PAYPAL_CLIENT_SECRET'));
		$payment->paymentInfo($id);
		echo("controller");
		$payment->execute();
	}

	public function paymentinfo($id){
		$payment = new Payment(env('PAYPAL_CLIENT_ID'), env('PAYPAL_CLIENT_SECRET'));
		return json_encode($payment->paymentInfo($id));

	}
}
