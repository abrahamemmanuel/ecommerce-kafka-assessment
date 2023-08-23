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
    protected object $cartItemRepository;
    protected object $cart_item;
    protected object | array $cart_items;

    public function __construct(CartItemRepository $cartItemRepository)
    {
        $this->cartItemRepository = $cartItemRepository;
    }

    public function addCartItem(array $data): bool
    {
        $this->cart_item = $this->cartItemRepository->create($data);
        if ($this->cart_item) {
            return true;
        }
        return false;
    }

    public function removeCartItem(int $user_id, int $product_id): bool
    {
        $this->cart_item = $this->cartItemRepository->findByUserAndProduct($user_id, $product_id);
        if ($this->cart_item) {
            $this->cartItemRepository->delete($this->cart_item);
            return true;
        }
        return false;
    }

    public function checkoutCart(int $user_id): bool
    {
        $this->cart_items = $this->cartItemRepository->getCartItemsByUser($user_id);
        if ($this->cart_items->count() > 0) {
            $this->order = $this->cartItemRepository->createOrder($user_id);
            foreach ($this->cart_items as $cart_item) {
                $this->cartItemRepository->createOrderItem($this->order->id, $cart_item->product_id);
            }
            $this->cartItemRepository->deleteCartItems($this->cart_items);
            return true;
        }
        return false;
    }
}
