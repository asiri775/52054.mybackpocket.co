<?php

namespace App\Vendors\Market;

use App\Helpers\ChatGptHelper;
use App\Vendors\Base\VendorInterface;

class Apple extends VendorInterface
{

    public function parse()
    {
        // echo $this->plainText;die;
        return ChatGptHelper::init()->generateResponse(
            $this->chatGptPrompt(),
            $this->plainText
        );
    }

    public function chatGptPrompt()
    {
        return 'You will be provided with order summary/order invoice from email received on client email. This orders can be of electronics purchases of repair bill. You have to analyse properly order summary. Extract below data:-
    
        1. Order items with product name, price, quantity and other info as details, repaired item name can be also used as product name, if qualtity not given take as 1 
        2. Order date
        3. Email date
        4. discounts total
        5. sub total
        6. tax amount
        7. total
        8. payment method
        9. vendor email, name and address
        10. Payment details - which can have card issuer, card number
    11. order no is repair id or transaction no
        
        All this data should return in json format given below, keys should strictly not be changed, json structure should also not be changed, if any json key data not found, pass null, but return all json data in same format. In json in products keys you can append object of items order, sample of that also given in below json.
        
        {
            "vendor": {
                "email":"info@vendor.com",
                "name":"Apple",
                "address":"210, Milian Street, Toronto, ON, Canada"
            },
            "products":[
                {"name":"IPAD OOW BATTERY REPAIR", "price":120, "quantity":1, "details":"Part Number: S4385Z/A, Warranty Code"},
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
        
        Donot return data given in json sample, if same data not found, return it as empty'; 
    }

}
