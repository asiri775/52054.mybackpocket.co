<?php

namespace App\Vendors\Market;

use App\Helpers\ChatGptHelper;
use App\Vendors\Base\VendorInterface;

class Moneris extends VendorInterface
{

    public function parse()
    {
        $content = $this->plainText;
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
        This is receipt of Moneris
        You have to analyse properly order summary. Extract below data:-
    
        1. order items not given so take product name is payment, quantity is 1 , price is amount paid before tip
        2. Order date and time is given use that just before amount given before REFERENCE
        3. Email date
        4. discounts are 0
        5. sub total is same as amount given before REFERENCE
        6. tax amount is 0, but can take tip amount in tax
        7. total - is given after TIP 
        8. payment method if given
        9. vendor email, name and address, email like do-not-reply@sportchek.ca
        10. Payment details - which can have card issuer, card number

        All this data should return in json format given below, keys should strictly not be changed, json structure should also not be changed, if any json key data not found, pass null, but return all json data in same format. In json in products keys you can append object of items order, sample of that also given in below json.
        
        {
            "vendor": {
                "email":"receipts@moneris.com",
                "name":"Apple",
                "address":"210, Milian Street, Toronto, ON, Canada"
            },
            "products":[
                {"name":"Payment", "price":120, "quantity":1}
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

Super Important Note:- order no used in transaction is JSON, you can take it from content after REFERENCE # before Auth. It should never be empty.
        
        Donot return data given in json sample, if same data not found, return it as empty';
    }

}
