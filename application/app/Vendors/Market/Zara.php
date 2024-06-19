<?php

namespace App\Vendors\Market;

use App\Helpers\ChatGptHelper;
use App\Vendors\Base\VendorInterface;

class Zara extends VendorInterface
{

    public function parse()
    {
        $plainText = $this->plainText;
        $plainText = explode('You have expressly requested the issuance', $plainText);
        return ChatGptHelper::init()->generateResponse(
            $this->chatGptPrompt(),
            $plainText[0]
        );
    }

    public function chatGptPrompt()
    {
        return 'You will be provided with zara digital receipt. You have to analyse properly order invoice without any errors. Extract below data:-
    
        1. Transaction Number or Trans: for order_no
        2. Vendor name like zara, their email and store address for vendor details
        3. Order date and time for transaction_date
        4. products information where tsp + code + clothe type is product name, put code in details as well, take its quantity and price per item to use in products
        5. Subtotal for transaction
        6. HST/tax for tax_amount
        7. discount if any, other take discount as 0
        8. total after hst and discount
        9. payment method like card/cash
        10. Payment details - which can have card issuer, card number, reference number, appr code, invoice
        
        All this data should return in json format given below, keys should strictly not be changed, json structure should also not be changed, if any json key data not found, pass null, but return all json data in same format. In json in products keys you can append object of items order, sample of that also given in below json.
        
        {
            "vendor": {
                "email":"email@vendor.com",
                "name":"Zara",
                "address":"210, Milian Street, Toronto, ON, Canada"
            },
            "products":[
                {"name":"Sweater", "price":120, "quantity":2, "details":"Code:- 98423749"}
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
        
        Donot return data given in json sample, if same data not found, return it as empty not null.
        Always return full json response'; 
    }

}
