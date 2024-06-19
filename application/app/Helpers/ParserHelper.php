<?php


namespace App\Helpers;

use App\Constants\Constants;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\Vendor;
use App\Vendors\Base\VendorHelper;
use Webklex\IMAP\Client;


class ParserHelper
{

    public static function run()
    {

        $imapSettings = Setting::whereNotNull('IMAP_PASSWORD')->orderBy('imap_last_checked_on')->limit(10)->get();
        foreach ($imapSettings as $imapSetting) {
            $lastCheckedOn = $imapSetting->imap_last_checked_on;
            if ($lastCheckedOn == null) {
                $lastCheckedOn = date(Constants::PHP_DATE_FORMAT, strtotime("-1 day"));
            }

            $imapSetting->imap_last_checked_on = date(Constants::PHP_DATE_FORMAT);
            $imapSetting->save();

            $oClient = new Client([
                'host' => $imapSetting->IMAP_HOST,
                'port' => $imapSetting->IMAP_PORT,
                'encryption' => $imapSetting->IMAP_ENCRYPTION,
                'validate_cert' => $imapSetting->IMAP_VALIDATE_CERT,
                'username' => $imapSetting->IMAP_USERNAME,
                'password' => $imapSetting->IMAP_PASSWORD,
                'protocol' => $imapSetting->IMAP_PROTOCOL
            ]);

            //Connect to the IMAP Server
            $oClient->connect();

            //Get all Mailboxes
            /** @var \Webklex\IMAP\Support\FolderCollection $aFolder */
            $aFolder = $oClient->getFolder('INBOX');
            $tmpDir = VendorHelper::getTempDir();
            //Loop through every Mailbox    
            /** @var \Webklex\IMAP\Folder $oFolder */
            //Get all Messages of the current Mailbox $oFolder
            /** @var \Webklex\IMAP\Support\MessageCollection $aMessage */
            // $aMessage = $aFolder->messages()->all()->get();
            // $aMessage = $aFolder->query()->since(date('d.m.Y', strtotime($lastCheckedOn)))->unseen()->limit(1, 1)->setFetchFlags(true)->get();
            $aMessage = $aFolder->query()->unseen()->limit(1, 1)->setFetchFlags(true)->get();

            /** @var \Webklex\IMAP\Message $oMessage */
            foreach ($aMessage as $oMessage) {
                $emailDate = $oMessage->getDate();
                if ($oMessage->hasAttachments()) {
                    $attachment = $oMessage->getAttachments()[0];
                    $pdfFile = "{$tmpDir}/{$attachment->getName()}";
                    file_put_contents($pdfFile, $attachment->getContent());
                    $body = VendorHelper::pdfOCR($pdfFile);
                    unlink($pdfFile);
                } else {
                    $body = $oMessage->getHTMLBody(true);
                }
                if ($body) {
                    $sender_array = collect($oMessage->getSender());
                    $sender = $sender_array->first();

                    $body = preg_replace("/\r|\n|\t/", "", $body);
                    $htmlBody = $body;

                    //Parse HTML into String
                    $plainText = preg_replace('/<.*?>/', "\n", $htmlBody);

                    //Getting vendor from email
                    $vendor = VendorHelper::getVendor($plainText);
                    // var_dump($vendor);die;

                    if ($vendor) {

                        $vendor_name = $vendor['name'];
                        $vendor_class = $vendor['class'];

                        $v_class = VendorHelper::getVendorClass($vendor_class);
                        $v_content = new $v_class($htmlBody, $plainText, $emailDate, $sender, $oMessage->getMessageId());
                        $content = $v_content->parse();

                        // print_r($content);die;

                        if ($content && is_array($content)) {

                            try {

                                if ($content['vendor']['name'] == null) {
                                    $content['vendor']['name'] = $vendor_name;
                                }

                                $vendor = Vendor::firstUpdateOrCreate(
                                    ['name' => $content['vendor']['name']],
                                    $content['vendor']
                                );

                                $products = $content['products'];

                                $transaction = Transaction::where('order_no', $content['transaction']['order_no'])
                                    ->where('user_id', $imapSetting->user_id)
                                    ->where('vendor_id', $vendor->id)
                                    ->first();

                                if ($transaction == null) {
                                    $transaction_data = [
                                        "sub_total" => $content['transaction']['sub_total'],
                                        "total" => $content['transaction']['total'],
                                        "discount" => $content['transaction']['discount'],
                                        "tax_amount" => $content['transaction']['tax_amount'],
                                        "payment_method" => $content['transaction']['payment_method'],
                                        "payment_ref" => $content['transaction']['payment_details'],

                                        "order_no" => $content['transaction']['order_no'],
                                        "transaction_no" => $content['transaction']['order_no'],
                                        "json" => json_encode($content['transaction']),
                                        "transaction_date" => date('Y-m-d H:i:s', strtotime($content['transaction']['transaction_date'])),
                                        "transaction_time" => date('H:i:s', strtotime($content['transaction']['transaction_date'])),
                                        "user_id" => $imapSetting->user_id,
                                        "vendor_id" => $vendor->id,
                                        "message_id" => $oMessage->getMessageId()
                                    ];
                                    $transaction = Transaction::create($transaction_data);
                                    $transaction = Transaction::where('order_no', $content['transaction']['order_no'])
                                        ->where('vendor_id', $vendor->id)
                                        ->first();
                                }

                                foreach ($products as $p) {

                                    $purchase_data = [
                                        'transaction_id' => $transaction->id,
                                        'price' => $p['price'],
                                        'quantity' => $p['quantity'] ?? 1,
                                    ];

                                    $product = Product::firstOrCreate(
                                        [
                                            'name' => $p['name'],
                                            'vendor_id' => $vendor->id
                                        ],
                                        [
                                            'name' => $p['name'],
                                            'price' => $p['price'],
                                            'vendor_id' => $vendor->id
                                        ]
                                    );

                                    $purchase_data['product_id'] = $product->id;

                                    if ($transaction != null) {
                                        $purchase = new Purchase();
                                        $purchase->transaction_id = $purchase_data['transaction_id'];
                                        $purchase->product_id = $purchase_data['product_id'];
                                        $purchase->price = round($purchase_data['price'], 2);
                                        $purchase->quantity = $purchase_data['quantity'];
                                        $purchase->save();
                                    }
                                }

                            } catch (\Exception $ex) {

                                Helper::saveLogs("imap parse failed", 'imapParse', [
                                    'exception' => $ex,
                                    'vendor' => $vendor,
                                    'content' => $content,
                                    'userId' => $imapSetting->user_id
                                ]);

                            }

                        }

                    }
                }
            }
        }
    }

}