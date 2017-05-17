<?php

Route::get('paypal/demo', "Coderscoopinc\Laravelpaypal\Http\PaypalController@index");

Route::get('paypal/confirm/{id}', "Coderscoopinc\Laravelpaypal\Http\PaypalController@confirmpayment");
Route::get('paypal/info/{id}', "Coderscoopinc\Laravelpaypal\Http\PaypalController@paymentInfo");
Route::post('paypal/payment', "Coderscoopinc\Laravelpaypal\Http\PaypalController@paymentWithSalesData");

