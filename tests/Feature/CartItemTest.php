<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Tests\TestCase;

class CartItemTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function should_add_items_to_cart(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $response = $this->postJson('/api/v1/cart/add-items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
        $this->assertDatabaseHas('cart_items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'message' => 'Item added to cart'
        ]);
    }

    /**
     * @test
     */
    public function should_not_add_items_to_cart_when_user_id_is_not_provided(): void
    {
        $product = Product::factory()->create();
        $response = $this->postJson('/api/v1/cart/add-items', [
            'product_id' => $product->id,
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
        $user = User::factory()->create();
        $response = $this->postJson('/api/v1/cart/add-items', [
            'user_id' => $user->id,
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
        $user = User::factory()->create();
        $response = $this->postJson('/api/v1/cart/add-items', [
            'user_id' => $user->id,
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
        $product = Product::factory()->create();
        $response = $this->postJson('/api/v1/cart/add-items', [
            'user_id' => 'string',
            'product_id' => $product->id,
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

    /**
     * @test
     */
    public function should_remove_items_from_cart(): void
    {
        $cart_item = CartItem::factory()->create();
        $response = $this->deleteJson('/api/v1/cart/remove-items', [
            'user_id' => $cart_item->user_id,
            'product_id' => $cart_item->product_id,
        ]);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message' => 'Item removed from cart'
        ]);
        $this->assertDatabaseMissing('cart_items', [
            'user_id' => $cart_item->user_id,
            'product_id' => $cart_item->product_id,
        ]);
    }

    /**
     * @test
     */
    public function should_not_remove_items_from_cart_when_user_id_is_not_provided(): void
    {
        $cart_item = CartItem::factory()->create();
        $response = $this->deleteJson('/api/v1/cart/remove-items', [
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
    public function should_not_remove_items_from_cart_when_product_id_is_not_provided(): void
    {
        $cart_item = CartItem::factory()->create();
        $response = $this->deleteJson('/api/v1/cart/remove-items', [
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
    public function should_check_out_cart(): void
    {
        $cart_item = CartItem::factory()->create();
        $response = $this->postJson('/api/v1/cart/checkout', [
            'user_id' => $cart_item->user_id,
            'product_id' => $cart_item->product_id,
        ]);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message' => 'Cart checked out successfully',
        ]);
        $this->assertDatabaseMissing('cart_items', [
            'user_id' => $cart_item->user_id,
        ]);
    }

    /**
     * @test
    */
    public function should_not_check_out_empty_cart(): void
    {
        $cart_item = CartItem::factory()->create();
        $response = $this->postJson('/api/v1/cart/checkout', [
            'user_id' => $cart_item->user_id,
            'product_id' => $cart_item->product_id,
        ]);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message' => 'Cart checked out successfully',
        ]);
        $this->assertDatabaseMissing('cart_items', [
            'user_id' => $cart_item->user_id,
        ]);
        $response = $this->postJson('/api/v1/cart/checkout', [
            'user_id' => $cart_item->user_id,
            'product_id' => $cart_item->product_id,
        ]);
        $response->assertStatus(404);
        $response->assertJsonFragment([
            'message' => 'Cart is empty',
        ]);
    }
}
