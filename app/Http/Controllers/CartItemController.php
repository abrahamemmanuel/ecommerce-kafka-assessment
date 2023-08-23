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
use App\Services\KafkaProducerService;

class CartItemController extends Controller implements CartItemInterface
{
    protected object $cartItemService;
    protected object $kafkaProducerService;

    public function __construct(CartItemService $cartItemService, KafkaProducerService $kafkaProducerService)
    {
        $this->cartItemService = $cartItemService;
        $this->kafkaProducerService = $kafkaProducerService;
    }

    public function add(CartItemRequest $request): JsonResponse|Response
    {
        return $this->cartItemService->addCartItem($request->all())
            ?
                response()->json([
                    'message' => 'Item added to cart'
                ], Response::HTTP_CREATED)
            :
                response()->json([
                    'message' => 'Item not added to cart'
                ], Response::HTTP_BAD_REQUEST);
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