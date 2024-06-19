<?php

namespace App\Vendors\Market;

use App\Helpers\ChatGptHelper;
use App\Vendors\Base\VendorInterface;

class Bestbuy extends VendorInterface
{

    public function parse()
    {
        $content = $this->plainText;
        $content = explode('CUSTOMER COPY', $content);
        $content = $content[0];
        $content = explode('Open Box Product', $content);
        $content = $content[0];
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
        This is receipt of Bestbuy Receipt
        You have to analyse properly order summary. Extract below data:-

Important:- product price is between Custom Amount and Purchase Subtotal
    
        1. products items are given in content after SALES text, which have code and product name, and price
        2. Order date and time is given use that just before amount
        3. Email date
        4. discounts if  any
        5. sub total
        6. tax amount HST
        7. total after sub total and tax
        8. payment method after Record SALE it can be card/cash/cheuque
        9. vendor email, name and address, email like do-not-reply@besybuy.ca
        10. Payment details - which can have card issuer, card number

        All this data should return in json format given below, keys should strictly not be changed, json structure should also not be changed, if any json key data not found, pass null, but return all json data in same format. In json in products keys you can append object of items order, sample of that also given in below json.
        
        {
            "vendor": {
                "email":"non-respondable-email@bestbuy.ca",
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

Super Important Note:- order_no used in transaction is JSON, you can take it after Val #:
order_no is required to be filled in any case
        
        Donot return data given in json sample, if same data not found, return it as empty';
    }

}
