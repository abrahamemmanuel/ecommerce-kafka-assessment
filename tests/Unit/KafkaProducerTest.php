<?php

namespace Tests\Unit;

use Tests\TestCase;

use App\Services\KafkaProducerService;
use App\Services\CartItemService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Junges\Kafka\Facades\Kafka;

class KafkaProducerTest extends TestCase
{
    protected $kafkaProducerService;
    protected $cartItemService;

    public function setUp(): void
    {
        parent::setUp();
        $this->kafkaProducerService = new KafkaProducerService();
    }
    /**
     * A basic unit test example.
     * @test
     */
    public function should_publish_message_to_cart_items_topic(): void
    {
        Kafka::fake();
        $data = [
            'user_id' => 1,
            'product_id' => 1,
        ];
        $producer = $this->kafkaProducerService->produce($data);
        Kafka::assertPublishedOn('cart_items');
        $this->assertTrue($producer);
    }
}
