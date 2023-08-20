<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use App\Models\CartItem;
use Tests\TestCase;

class CartItemTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function should_add_items_to_cart(): void
    {
        $cart_item = CartItem::factory()->create();
        $response = $this->postJson('/api/v1/cart/add-items', [
            'user_id' => $cart_item->user_id,
            'product_id' => $cart_item->product_id,
        ]);
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'user_id' => $cart_item->user_id,
            'product_id' => $cart_item->product_id,
        ]);
        $this->assertDatabaseHas('cart_items', [
            'user_id' => $cart_item->user_id,
            'product_id' => $cart_item->product_id,
        ]);
    }

    /**
     * @test
     */
    public function should_not_add_items_to_cart_when_user_id_is_not_provided(): void
    {
        $cart_item = CartItem::factory()->create();
        $response = $this->postJson('/api/v1/cart/add-items', [
            'product_id' => $cart_item->product_id,
        ]);
        $response->assertStatus(422);
        $response->assertJsonFragment([
            'errors' => [
                'user_id' => [
                    'User ID is required'
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function should_not_add_items_to_cart_when_product_id_is_not_provided(): void
    {
        $cart_item = CartItem::factory()->create();
        $response = $this->postJson('/api/v1/cart/add-items', [
            'user_id' => $cart_item->user_id,
        ]);
        $response->assertStatus(422);
        $response->assertJsonFragment([
            'errors' => [
                'product_id' => [
                    'Product ID is required'
                ]
            ]
        ]);
    }

    /**
     * @test
    */
    public function should_not_add_items_to_cart_when_product_id_is_a_string(): void
    {
        $cart_item = CartItem::factory()->create();
        $response = $this->postJson('/api/v1/cart/add-items', [
            'user_id' => $cart_item->user_id,
            'product_id' => 'string',
        ]);
        $response->assertStatus(422);
        $response->assertJsonFragment([
            'errors' => [
                'product_id' => [
                    'Product ID must be an integer'
                ]
            ]
        ]);
    }

        /**
     * @test
    */
    public function should_not_add_items_to_cart_when_user_id_is_a_string(): void
    {
        $cart_item = CartItem::factory()->create();
        $response = $this->postJson('/api/v1/cart/add-items', [
            'user_id' => 'string',
            'product_id' => $cart_item->product_id,
        ]);
        $response->assertStatus(422);
        $response->assertJsonFragment([
            'errors' => [
                'user_id' => [
                    'User ID must be an integer'
                ]
            ]
        ]);
    }
}
