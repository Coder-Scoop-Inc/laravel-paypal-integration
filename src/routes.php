<?php

Route::get('paypal/demo', "Coderscoop\Laravelpaypal\Http\PaypalController@index");

Route::get('paypal/confirm/{id}', "Coderscoop\Laravelpaypal\Http\PaypalController@confirmpayment");
Route::get('paypal/info/{id}', "Coderscoop\Laravelpaypal\Http\PaypalController@paymentInfo");
Route::post('paypal/payment', "Coderscoop\Laravelpaypal\Http\PaypalController@paymentWithSalesData");

