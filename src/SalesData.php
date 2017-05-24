<?php

namespace Coderscoop\Laravelpaypal;
use Coderscoop\Laravelpaypal\Item;


class SalesData
{

	protected $data = null; 
	protected $return_url;
	protected $cancel_url;



	protected $itemList = [];

 /**
   * sales data constructor
   *
   * if supplied with a JSON data object it will use that, if not it will build one based on items pushed inot itemlist
   * 
   * @param JSON sales data object, also can send return and cancel url's these are where the user will land after 
   *the payment process 
   * 
   */
	public function __construct($salesData = null,$return_url = 'example.com', $cancel_url = 'example.com'){
 		if ($salesData != null){
 			$this->data = $salesData;
 		}
 		$this->return_url = $return_url;
		$this->cancel_url = $cancel_url;

	}
 /**
   *adds an item (a line item for the paymebnt, to the data object)
   *
   * 
   * @param item object
   * 
   */
	public function addItem($item){
		array_push($this->itemList, $item);
	}
 /**
   *converts itemlist to json object of items
   * @return JSON object based on item lists
   * 
   */
	public function item_list(){
		$items =  '"item_list": {"items":[';
		foreach ($this->itemList as $item){
			$items = $items . $item->toJson() . ',';
		}
		$items = substr($items, 0, -1); //remove the final comma
		$items = $items . ']}';

		return $items;

	}
	 /**
   *utility function to set currency bassed on the currency in item_list
   *
   * 
   * @return either the currency used or null if no items set.
   * 
   */

	public function currency(){
		if (count($this->itemList) > 0){
			return $this->itemList[0]->currency();
		}
	}

	 /**
   *totals the cost of all the line items in a payment
   * @return amount to be used in data object.  If this does not match the total of the line itmes the payment will 
   *fail
   * 
   */
	public function amountTotal(){
		$callback = function($carry,$value){
			return  0;//$carry += $value->cost();
		};

		if (count($this->itemList) > 0){
			return array_reduce($this->itemList, $callback, 0);
		}else{
			return "0";
		}
	}
 /**
   *builds salesdata object for a payment
   *
   * this is built based on items, if no data object is provided on instantiation
   * 
   * @return JSON object representing the sales data obkect for paypal.
   * 
   */
	public function salesData(){
		if ($this->data == null){
			try{
				$this->data = '{
		              "intent":"sale",
		              "redirect_urls":{
		                "return_url":"' . $this->return_url . '",
		                "cancel_url":"' . $this->cancel_url . '"
		              },
		              "payer":{
		                "payment_method":"paypal"
		              },
		              "transactions":[
		                {
		                  "amount":{
		                    "total":"' .$this->amountTotal() . '",
		                    "currency":"' .$this->currency() . '"
		                  },' . $this->item_list() . '
		             				
		                }
		              ]
		            }';
		    }catch (\Exception $ex) {
	            $error =  json_encode($ex->getMessage());
	            var_dump($error);
	        }
	   }
		return $this->data;
	}
}