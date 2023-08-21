<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Interfaces\CartItemInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Http\Requests\CartItemRequest;
use App\Services\CartItemService;
use App\Repositories\CartItemRepository;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;

class CartItemController extends Controller implements CartItemInterface
{
    protected $cartItemService;
    protected $cartItemRepository;

    public function __construct(CartItemService $cartItemService, CartItemRepository $cartItemRepository)
    {
        $this->cartItemService = $cartItemService;
        $this->cartItemRepository = $cartItemRepository;
    }

    public function add(CartItemRequest $request): JsonResponse|Response
    {
        return response()->json($this->cartItemService->addCartItem($request->validated()), Response::HTTP_CREATED);
    }

    public function remove(CartItemRequest $request): JsonResponse|Response
    {
        return $this->cartItemService->removeCartItem($request->user_id, $request->product_id)
            ?
                response()->json([
                    'message' => 'Item removed from cart'
                ], Response::HTTP_OK)
            :
                response()->json([
                    'message' => 'Item not found in cart'
                ], Response::HTTP_NOT_FOUND);
    }

    public function checkout(CartItemRequest $request): JsonResponse|Response
    {
        return $this->cartItemService->checkoutCart($request->user_id)
            ?
                response()->json([
                    'message' => 'Cart checked out successfully'
                ], Response::HTTP_OK)
            :
                response()->json([
                    'message' => 'Cart is empty'
                ], Response::HTTP_NOT_FOUND);
    }
}