<?php

namespace App\Vendors\Base;
abstract class VendorInterface
{

    public $htmlBody;
    public $plainText;
    public $emailDate;
    private $sender;
    private $message_id;
    public function __construct($htmlBody, $plainText, $emailDate, $sender, $message_id)
    {
        $this->htmlBody = $htmlBody;
        $this->plainText = $plainText;
        $this->emailDate = $emailDate;
        $this->message_id = $message_id;
        $this->sender = $sender;
    }

    abstract public function parse();
    abstract public function chatGptPrompt();

}