<?php
declare(strict_types=1);

namespace App\Services;

use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;
use App\Services\KafkaConsumerService;
use App\Services\CartItemService;

class KafkaProducerService
{
    protected $cartItemService;

    public function __construct(CartItemService $cartItemService)
    {
        $this->cartItemService = $cartItemService;
    }

    public function produce(array $data): bool
    {
        $new_message = new Message(
            body: [
                'user_id' => $data['user_id'],
                'product_id' => $data['product_id'],
            ]
        );
        $producer = Kafka::publishOn('cart_items')->withMessage($new_message);
        $consumer = \Junges\Kafka\Facades\Kafka::createConsumer(['cart_items'])->withHandler(new KafkaConsumerService($this->cartItemService));
        $consumer = $consumer->build();
        $consumer->consume();
        dump($producer);
        dump($consumer);
        return $producer->send();
    }
}