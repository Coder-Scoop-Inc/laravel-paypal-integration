#Paypal Payment API - Laravel integration

Developer Guin Kellam  



### DESCRIPTION
This package adds sevearl url end points to your project that make using the Paypal API signfiganly easier.  You will still need a paypal vendor account, and make sure you are using https, you know, for reasons.  

When you initiante a payment with the Paypal Payment API, a Payment object is created on their servers.  You then use there various REST end points to interact with the object throught the payment process.  This package simplifies this interaction for you. 

### INSTALLATION

composer require "coderscoop/laravelpaypal:*"

### USAGE
This paackage will add the following routes to your project

#### paypal/demo - GET 

This route allows you to test your credentials and post a sample payment; you can play with the endpoints and not have to worry about building up the JSON for a payment. Otherwise it behaves exactly like paypal/payment, and returns a data object of the same structure.

#### paypal/payment - POST 

Post a fully formed paypal payment object such as the following (more examples and information on these objects can be found at https://developer.paypal.com/docs/api/payments/) to this route.  It will return to you the paypal id for this transaction and the url you need to redirect your user to in order to approve the payment.

POST
*{
  "intent": "sale",
  "payer": {
  "payment_method": "paypal"
  },
  "transactions": [
  {
    "amount": {
    "total": "30.11",
    "currency": "USD",
    "details": {
      "subtotal": "30.00",
      "tax": "0.07",
      "shipping": "0.03",
      "handling_fee": "1.00",
      "shipping_discount": "-1.00",
      "insurance": "0.01"
    }
    },
    "description": "The payment transaction description.",
    "custom": "EBAY_EMS_90048630024435",
    "invoice_number": "48787589673",
    "payment_options": {
    "allowed_payment_method": "INSTANT_FUNDING_SOURCE"
    },
    "soft_descriptor": "ECHI5786786",
    "item_list": {
    "items": [
      {
      "name": "hat",
      "description": "Brown hat.",
      "quantity": "5",
      "price": "3",
      "tax": "0.01",
      "sku": "1",
      "currency": "USD"
      },
      {
      "name": "handbag",
      "description": "Black handbag.",
      "quantity": "1",
      "price": "15",
      "tax": "0.02",
      "sku": "product34",
      "currency": "USD"
      }
    ],
    "shipping_address": {
      "recipient_name": "Brian Robinson",
      "line1": "4th Floor",
      "line2": "Unit #34",
      "city": "San Jose",
      "country_code": "US",
      "postal_code": "95131",
      "phone": "011862212345678",
      "state": "CA"
    }
    }
  }
  ],
  "note_to_payer": "Contact us for any questions on your order.",
  "redirect_urls": {
  "return_url": "http://www.paypal.com/return",
  "cancel_url": "http://www.paypal.com/cancel"
  }
}*

RETURN 
*{"payment_id":"PAY-9JC48149SV546373BLENZEAY","approval_url":"https:\/\/www.sandbox.paypal.com\/cgi-bin\/webscr?cmd=_express-checkout&token=EC-68K84205DL435322P"}*

##### IMPORTANT NOTE : you must redirect your user to the approval link that this route returns, that is how they will log into paypal and approve the payment so you can get your money !!!

##### IMPORTANT NOTE 2 : make sure you save the payment id somewhere, this is how you will identify and interact with this payment in the next steps !

##### LESS IMPORTANT BUT STILL KIND OF IMPORTANT NOTE : return_url and cancel_url are url's you provide, pyapal will redirect the user to these routes depending on the results of the approval process.  See more information about this in the instructions for the confirm route.


An alternative to posting a JSON object to this route is to build your object programtically using the Item and SalesData objects in the package. This is also a straight forward process and will be explained later in this document.

#### paypal/info/{id} -GET

Anytime afer a payment has been created you can use this route to get it's current status and information.  Paypal also tracks lineitems, notes, etc, all of this is avaialble from this route. 

Send a get requests to this route, {id} is the payment_id that was returned when you created the payment with the paypal/payment route from this package.  

RETURN
A JSON object simialar to the one below, it will look different depending on the status of the payment you quieried
*{"id":"PAY-5S991868XB086112JLEO2BJY","intent":"sale","state":"created","cart":"3T903907ND8538129","payer":{"payment_method":"paypal"},"transactions":[{"amount":{"total":"4.00","currency":"CAD"},"payee":{"merchant_id":"DTW9Z49SZHQTG","email":"guinifer.k-facilitator@gmail.com"},"item_list":{"items":[{"name":"Thing1","sku":"1","description":"This is thing 1","price":"2.00","currency":"CAD","tax":"0.00","quantity":1},{"name":"Thing1","sku":"1","description":"This is thing 1","price":"2.00","currency":"CAD","tax":"0.00","quantity":1}]},"related_resources":[]}],"redirect_urls":{"return_url":"example.com\/?paymentId=PAY-5S991868XB086112JLEO2BJY","cancel_url":"example.com"},"create_time":"2017-05-18T13:24:54Z","update_time":"2017-05-18T13:33:23Z","links":[{"href":"https:\/\/api.sandbox.paypal.com\/v1\/payments\/payment\/PAY-5S991868XB086112JLEO2BJY","rel":"self","method":"GET"},{"href":"https:\/\/api.sandbox.paypal.com\/v1\/payments\/payment\/PAY-5S991868XB086112JLEO2BJY\/execute","rel":"execute","method":"POST"},{"href":"https:\/\/www.sandbox.paypal.com\/cgi-bin\/webscr?cmd=_express-checkout&token=EC-3T903907ND8538129","rel":"approval_url","method":"REDIRECT"}]}*



#### paypal/confirm/{id} - GET
Once a payment has been approved by the payer (using the approval url returned when the payment was created... see the paypal/payment POST route if you have forgotten about this) You can use the payment_id and this route to finalize the payment. If you do not do this the payment will not be finalized and you will not get paid !

RETURN
This will return a JSON object with the payment info if the payment has been apporved by the user, and if not, it will return a validation error.  A possible site flow would be to send this route as your return url to the paypal/payment POST route, so that when your application successfully returns from a the paypal site you automatically process the payments.  If you don't want to do this, you can check /paypal/info at anytime to see if the payment was approved, and then process it at your leisure.

#### Using the Item and Sales data models to build your payment
As an alternative to building your own payment JSON object and sending it with the paypal/payment POST route I have provided another method that you can use inside your own models.

There is an Item object, which is single line item from your sale and a a SalesData object, which hold the items and generates the JSON that will be sent to the paypal REST API.  Once you have built your SaleData you create a new Payment object with it, and then call the 'createPaypalPayment' method on it.  This submits your SalesData object and returns the same information that the paypal/payment POST route would (payment_id and approval_url).

##### Useful Methods
 Item Contstructor Item("name", "description", "quantity" , "price" , "tax" , "sku", "currency" )  NOTE:all of these values must be strings !!)
 
 SalesData->addItem(Item); 

 Payment Constructor Payment('CLIENT_ID', 'CLIENT_SECRET', SalesData)  NOTE: Client id and Client Secret are the ones provided by paypal through your developer account, I hope you have these set in your .env file !!  Do not expose theses to the world!!!)

Example

    $item1 = new Item("Thing1","This is thing 1","1","2","0.0","1","CAD");
    $item2 = new Item("Thing2","This is thing 2","1","2","0.0","1","CAD");

    $salesData = new SalesData();
    $salesData->addItem($item1);
    $salesData->addItem($item2);

    $payment = new Payment(env('PAYPAL_CLIENT_ID'), env('PAYPAL_CLIENT_SECRET'),$salesData);
  
    $payment_id = $payment->createPaypalPayment();

To Do

  More tests !

  More graceful error handling

  Add /paypal/cancel 

  Implement other REST fucntions (Set up reoccuring payments, invoicing, etc)

