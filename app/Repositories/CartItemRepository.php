<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Collection;

class CartItemRepository
{
    public function create(array $data): CartItem
    {
        return CartItem::create($data);
    }

    public function findByUserAndProduct(int $user_id, int $product_id): CartItem | null
    {
        return CartItem::where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->first();
    }

    public function getCartItemsByUser(int $user_id): Collection | array
    {
        return CartItem::where('user_id', $user_id)->get();
    }

    public function delete(CartItem | array $cart_item): void
    {
        $cart_item->delete();
    }

    public function createOrder(int $user_id): Order
    {
        return Order::create([
            'user_id' => $user_id
        ]);
    }

    public function createOrderItem(int $order_id, int $product_id): OrderItem
    {
        return OrderItem::create([
            'order_id' => $order_id,
            'product_id' => $product_id
        ]);
    }

    public function deleteCartItems(Collection | array $cart_items): void
    {
        $cart_items->each->delete();
    }
}
