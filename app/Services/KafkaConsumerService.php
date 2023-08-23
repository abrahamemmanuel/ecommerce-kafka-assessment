<?php

namespace App\Services;

use App\Repositories\CartItemService;
use Junges\Kafka\Facades\Kafka;

class KafkaConsumerService
{
    protected $cartItemService;

    public function __construct($cartItemService)
    {
        $this->cartItemService = $cartItemService;
    }

    public function __invoke(\Junges\Kafka\Contracts\KafkaConsumerMessage $message){
        //Array callback has to contain indices 0 and 1
        //Index 0 is the topic name
        //Index 1 is the message
        // dump($message->getTopicName());
        // dump($message);
        $this->cartItemService->addCartItem($message->getBody());
    }
}