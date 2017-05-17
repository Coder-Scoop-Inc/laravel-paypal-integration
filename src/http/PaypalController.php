<?php

namespace Coderscoop\Laravelpaypal\Http;


use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Coderscoop\Laravelpaypal\Payment;
use Coderscoop\Laravelpaypal\SalesData;
use Coderscoop\Laravelpaypal\Item;

/**
* PaypalController is the controll object for payments etc.
*/

class PaypalController extends Controller{

	/**
	 * From route GET /paypaldemo, is used to demonstraight functionality without building a salesData itme
	 *
	 * Builds 2 sample items, adds them to the sales data, then creates a paypal object
	 * total. Returns total.
	 * redirect to approval_url for user's payment info
	 *
	 * @param none
	 * @return JSON object with payment id from paypal and approval url for end user
	 * 
	 */

	public function index(){
		//$return_url = "example.com";
		$item1 = new Item("Thing1","This is thing 1","1","2","0.0","1","CAD");
		$item2 = new Item("Thing2","This is thing 2","1","2","0.0","1","CAD");

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

	/**
	 * From route POST /paypal/payment, create a paypal payment object
	 *
	 * starts paypal process by creating a paypal payment with data supplied via JSON object
	 * redirect to approval_url for user's payment info
	 * 
	 * @param JSON paypal payment details, more details can be found at https://developer.paypal.com/docs/api/
	 * payments/
	 * @return JSON object with payment id from paypal and approval url for end user
	 * 
	 */

	public function paymentWithSalesData(Request $request){
		$salesData = new SalesData($request->salesdata);

		$headers =  json_encode(['headers' => [
						        'Accept' => 'application/json',
						        'Accept-Languge' => 'en_US']
						       ]);
		$payment = new Payment(env('PAYPAL_CLIENT_ID'), env('PAYPAL_CLIENT_SECRET'),$salesData);
		$payment_id = $payment->createPaypalPayment();
		return json_encode(array('payment_id' => $payment_id, 'approval_url' => $payment->approval_url()));

	}

	/**
	 * From route GET paypal/confirm/{id}, confirms a payment that has been approved by user
	 *
	 * supplied with payment id, this uses payer_id from payment approval (this is done behind the scenes) to
	 * finalize a payment after a user has
	 * approved it
	 * 
	 * 
	 * @param payment id from paypal, this is returned from /paypal/payment
	 * @return JSON object with payment info from paypal
	 * 
	 */

	public function confirmpayment($id){
		$payment = new Payment(env('PAYPAL_CLIENT_ID'), env('PAYPAL_CLIENT_SECRET'));
		$payment->paymentInfo($id);
		return $payment->execute();

	}

	/**
	 * From route GET paypal/info/{id}, returns payment details
	 *
	 * use payer_id from payment approval (this is done behind the scenes) to finalize a payment after a user has
	 * approved it
	 * 
	 * 
	 * @param payment id from paypal, this is returned from /paypal/payment
	 * @return JSON object with payment info from paypal
	 * 
	 */

	public function paymentinfo($id){
		$payment = new Payment(env('PAYPAL_CLIENT_ID'), env('PAYPAL_CLIENT_SECRET'));
		return json_encode($payment->paymentInfo($id));

	}
}
