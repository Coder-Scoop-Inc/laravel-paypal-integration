# laravel-paypal-integration
Developer Guin Kellam  

## THIS DOCUMENT IS STILL IN PROGRESS - IT WILL BE READY FOR USE SHORTLY

Paypal Payment API - Laravel integration

### DESCRIPTION
This package is adds sevearl url end points to your project that make using the Paypal API signfiganly easier.  You will still need a paypal vendor account, and make sure you are using https, you know, for reasons.  

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

An alternative to posting a JSON object to this route is to build your object programtically using the Item and SalesData objects in the package. This is also a straight forward process and will be explained later in this document.

#### paypal/confirm/{id} - GET
once a 

#### paypal/info/{id} -GET