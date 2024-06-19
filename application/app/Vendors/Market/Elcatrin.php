<?php

namespace App\Vendors\Market;

use App\Helpers\ChatGptHelper;
use App\Vendors\Base\VendorInterface;

class Elcatrin extends VendorInterface
{

    public function parse()
    {
        $content = $this->htmlBody;
        return ChatGptHelper::init()->generateResponse(
            $this->chatGptPrompt(),
            $content
        );
    }

    public function chatGptPrompt()
    {
        return '
        Ignore text given in ============ Forwarded message ============ and content after regrads
        
        You will be provided with order summary/order invoice from email received on client email.
        This is receipt of El Catrin Destileria
        You have to analyse properly order summary. Extract below data:-
    
        1. order item is payment, quantity is 1 , price is amount paid
        2. Order date and time is given use that just before amount
        3. Email date
        4. discounts are 0
        5. sub total is same as  amount
        6. tax amount is 0
        7. total - sub total is same as amount
        8. payment method if given
        9. vendor email, name and address, email like do-not-reply@ELCATRIN.CA
        10. Payment details - which can have card issuer, card number

        All this data should return in json format given below, keys should strictly not be changed, json structure should also not be changed, if any json key data not found, pass null, but return all json data in same format. In json in products keys you can append object of items order, sample of that also given in below json.
        
        {
            "vendor": {
                "email":"info@NGOCYENRESTAURANT.COM",
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

Note:- order no used in transaction is JSON, you can take it from "a" html tag, which text is "full transaction receipt", take href value of it as order_no
        
        Donot return data given in json sample, if same data not found, return it as empty';
    }

}
