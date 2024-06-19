<?php

namespace App\Vendors\Market;

use App\Helpers\ChatGptHelper;
use App\Vendors\Base\VendorInterface;

class Riley extends VendorInterface
{

    public function parse()
    {
        return ChatGptHelper::init()->generateResponse(
            $this->chatGptPrompt(),
            $this->plainText  
        );
    }

    public function chatGptPrompt()
    {
        return '
        Ignore text given in ============ Forwarded message ============ and content after regrads
        
        You will be provided with order summary/order invoice from email received on client email.
        This is receipt of Rileys
        You have to analyse properly order summary. Extract below data:-

Important:- product price is between Custom Amount and Purchase Subtotal
    
        1. products have only 1 product name is payment, quantity is 1 , product price is between Custom Amount and Purchase Subtotal, quantity is 1
        2. Order date and time is given use that just before amount
        3. Email date
        4. discounts are 0
        5. sub total is Purchase Subtotal
        6. tax amount is 0 but take tip as tax
        7. total - sub total and tax
        8. payment method if given
        9. vendor email, name and address, email like do-not-reply@sportchek.ca
        10. Payment details - which can have card issuer, card number

        All this data should return in json format given below, keys should strictly not be changed, json structure should also not be changed, if any json key data not found, pass null, but return all json data in same format. In json in products keys you can append object of items order, sample of that also given in below json.
        
        {
            "vendor": {
                "email":"info@rileys.COM",
                "name":"Apple",
                "address":"210, Milian Street, Toronto, ON, Canada"
            },
            "products":[
                {"name":"Mac Book Pro", "price":120, "quantity":1, "details":"16 GB RAM"}
            ],
            "transaction":{
                "order_no":1111,
                "transaction_date":"2024-10-22 03:02:22",
                "sub_total":120,
                "discount";20,
                "total":100,
                "tax_amount":10,
                "payment_method":"card",
                "payment_details":"card issuer, card number, reference number",
                "emailDate":"2024-10-22 03:02:22"
            }
        }

Super Important Note:- order_no used in transaction is JSON, you can take it from content after between Ref No. and Terminal ID:
order_no is required to be filled in any case
        
        Donot return data given in json sample, if same data not found, return it as empty';
    }

}
