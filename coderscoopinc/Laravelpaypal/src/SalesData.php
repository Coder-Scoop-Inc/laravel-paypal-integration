<?php

namespace Coderscoopinc\Laravelpaypal;
use Coderscoopinc\Laravelpaypal\Item;


class SalesData
{

	protected $data; 
	protected $return_url ="example.com";
            

	protected $itemList = [];

	
	public function __construct(){
		$this->data = '{
              "intent":"sale",
              "redirect_urls":{
                "return_url":"' . $this->return_url . '",
                "cancel_url":"http://example.com/your_cancel_url.html"
              },
              "payer":{
                "payment_method":"paypal"
              },
              "transactions":[
                {
                  "amount":{
                    "total":"4",
                    "currency":"CAD"
                  },
             "item_list": {"items":[{"name":"Thing1","description":"This is thing 1","quantity":"1","price":"2","tax":"0.01","sku":"2342","currency":"CAD"},{"name":"Thing2","description":"This is thing 2","quantity":"1","price":"2","tax":"0.01","sku":"2342","currency":"CAD"}]}
                }
              ]
            }';
		
	}

	public function addItem($item){
		array_push($this->itemList, $item);
	}

	public function item_list(){
		$items =  '"item_list": {"items":[';
		foreach ($this->itemList as $item){
			$items = $items . $item->toJson() . ',';
		}
		$items = substr($items, 0, -1); //remove the final comma
		$items = $items . ']}';

		return $items;

	}

	public function toJson(){
		$data = '{
              "intent":"sale",
              "redirect_urls":{
                "return_url":"' . $this->return_url . '",
                "cancel_url":"http://example.com/your_cancel_url.html"
              },
              "payer":{
                "payment_method":"paypal"
              },
              "transactions":[
                {
                  "amount":{
                    "total":"4",
                    "currency":"CAD"
                  },' . $this->item_list() . '
             				
                }
              ]
            }';
		return $data;
	}
}