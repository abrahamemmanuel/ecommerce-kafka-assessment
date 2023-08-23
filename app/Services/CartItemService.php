<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\CartItemRepository;
use App\Services\KafkaProducerService;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;

class CartItemService
{
    protected $cartItemRepository;

    public function __construct(CartItemRepository $cartItemRepository)
    {
        $this->cartItemRepository = $cartItemRepository;
    }

    public function addCartItem(array $data): void
    {
        $this->cartItemRepository->create($data);
    }

    public function removeCartItem(int $user_id, int $product_id): bool
    {
        $cart_item = $this->cartItemRepository->findByUserAndProduct($user_id, $product_id);
        
        if ($cart_item) {
            $this->cartItemRepository->delete($cart_item);
            return true;
        }

        return false;
    }

    public function checkoutCart(int $user_id): bool
    {
        $cart_items = $this->cartItemRepository->getCartItemsByUser($user_id);

        if ($cart_items->count() > 0) {
            $order = $this->cartItemRepository->createOrder($user_id);

            foreach ($cart_items as $cart_item) {
                $this->cartItemRepository->createOrderItem($order->id, $cart_item->product_id);
            }

            $this->cartItemRepository->deleteCartItems($cart_items);
            return true;
        }

        return false;
    }
}
