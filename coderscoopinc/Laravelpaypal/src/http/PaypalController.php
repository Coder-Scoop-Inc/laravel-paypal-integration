<?php

namespace Coderscoopinc\Laravelpaypal\Http;


use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Coderscoopinc\Laravelpaypal\Payment;
use Coderscoopinc\Laravelpaypal\SalesData;
use Coderscoopinc\Laravelpaypal\Item;



class PaypalController extends Controller{
	public function test(){
		$item1 = new Item("Thing1","This is thing 1","1","2","0.0"."1","2342","CAD");
		$item2 = new Item("Thing2","This is thing 2","1","2","0.0"."1","2342","CAD");

		$salesData = new SalesData();
		$salesData->addItem($item1);
		$salesData->addItem($item2);

		echo $salesData->toJson();
	}

	public function index(){
		//$return_url = "example.com";

		$item1 = new Item("Thing1","This is thing 1","1","2","0.0"."1","2342","CAD");
		$item2 = new Item("Thing2","This is thing 2","1","2","0.0"."1","2342","CAD");

		$salesData = new SalesData();
		$salesData->addItem($item1);
		$salesData->addItem($item2);

		$headers =  json_encode(['headers' => [
						        'Accept' => 'application/json',
						        'Accept-Languge' => 'en_US']
						       ]);
		$payment = new Payment(env('PAYPAL_CLIENT_ID'), env('PAYPAL_CLIENT_SECRET'),$salesData);
	
		$payment_id = $payment->createPaypalPayment();
		return json_encode(array('payment_id' => $payment_id, 'approval_url' => $payment->approval_url()));
		//return redirect($payment->approval_url());
;
	}

	public function confirmpayment($id){
		$payment = new Payment(env('PAYPAL_CLIENT_ID'), env('PAYPAL_CLIENT_SECRET'));
		$payment->paymentInfo($id);
		return $payment->execute();

	}

	public function paymentinfo($id){
		$payment = new Payment(env('PAYPAL_CLIENT_ID'), env('PAYPAL_CLIENT_SECRET'));
		return json_encode($payment->paymentInfo($id));

	}
}
