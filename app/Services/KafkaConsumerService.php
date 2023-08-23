<?php

namespace App\Services;

use App\Repositories\CartItemService;
use Junges\Kafka\Facades\Kafka;

class KafkaConsumerService
{
    public function __invoke(\Junges\Kafka\Contracts\KafkaConsumerMessage $message){
        //Array callback has to contain indices 0 and 1
        //Index 0 is the topic name
        //Index 1 is the message
        // dump($message->getTopicName());
        // dump($message);
        // $this->cartItemService->addCartItem($message->getBody());
    }
}