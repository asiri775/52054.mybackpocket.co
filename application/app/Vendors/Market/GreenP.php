<?php

namespace App\Vendors\Market;

use App\Helpers\ChatGptHelper;
use App\Vendors\Base\VendorInterface;

class GreenP extends VendorInterface
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
        return 'You will be provided with parking ticket order summary. You have to analyse properly text without any errors. Extract below data:-
    
        1.Transaction Number
        2 Location
        3. End Date as Transaction Date
        4. Parking fee as subtotal
        5. total
        6. use payment info to populate payment_method and payment_details 
        7. vendor email, name and address, address of vendor will be location name
8. Use start and end as product detail, product name will be Parking Receipt, price is parking fee, quantity is 1
9. discount is 0, tax is 0
10. Email date is end date
        
        All this data should return in json format given below, keys should strictly not be changed, json structure should also not be changed, if any json key data not found, pass null, but return all json data in same format. In json in products keys you can append object of items order, sample of that also given in below json.
        
        {
            "vendor": {
                "email":"burgerking@gmail.com",
                "name":"Burger King",
                "address":"210, Milian Street, Toronto, ON, Canada"
            },
            "products":[
                {"name":"Parking Receipt", "price":120, "quantity":2, "details":"From 25 may 4:40 AM to 26 May 5:00 PM"}
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
        
        Donot return data given in json sample, if same data not found, return it as empty not null'; 
    }

}
