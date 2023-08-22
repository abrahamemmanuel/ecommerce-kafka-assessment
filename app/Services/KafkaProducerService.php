<?php
declare(strict_types=1);

namespace App\Services;

use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;
use Illuminate\Http\Request;

class KafkaProducerService
{
    public function produce(array $data): void
    {
        $new_message = new Message(
            body: [
                'user_id' => $data['user_id'],
                'product_id' => $data['product_id'],
            ]
        );
        $producer = Kafka::publishOn('cart_items')->withMessage($new_message);
        $producer->send();
    }
}