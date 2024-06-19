<?php

namespace App\Helpers;

use Orhanerday\OpenAi\OpenAi;

class ChatGptHelper
{

    public $openAI = null;
    private static $instance = null;

    public function __construct()
    {
        $this->openAI = new OpenAi(env('CHAT_GPT_KEY'));
    }

    public static function init(): ChatGptHelper
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function generateResponse($prompt, $message)
    { 
        try {
            $chat = $this->openAI->chat([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        "role" => "system",
                        "content" => $prompt
                    ],
                    [
                        "role" => "user",
                        "content" => $message
                    ],
                ],
                'temperature' => 1.0,
                'frequency_penalty' => 0,
                'presence_penalty' => 0,
            ]);
            $d = json_decode($chat);
            return json_decode($d->choices[0]->message->content, true);
        } catch (\Exception $ex) {
            return null;
        }
    }

}