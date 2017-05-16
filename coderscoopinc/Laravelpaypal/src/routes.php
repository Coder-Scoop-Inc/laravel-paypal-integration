<?php

Route::get('paypal', "Coderscoopinc\Laravelpaypal\Http\PaypalController@index");

Route::get('confirmpayment/{id}', "Coderscoopinc\Laravelpaypal\Http\PaypalController@confirmpayment");
Route::get('paymentinfo/{id}', "Coderscoopinc\Laravelpaypal\Http\PaypalController@paymentInfo");

Route::get('test', "Coderscoopinc\Laravelpaypal\Http\PaypalController@test");
