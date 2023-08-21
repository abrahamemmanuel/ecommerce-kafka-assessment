<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Interfaces\CartItemInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Http\Requests\CartItemRequest;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;

class CartItemController extends Controller implements CartItemInterface
{
    public function add(CartItemRequest $request): JsonResponse|Response
    {
        return response()->json(CartItem::create($request->validated()), Response::HTTP_CREATED);
    }

    public function remove(CartItemRequest $request): JsonResponse|Response
    {
        $cart_item = CartItem::where('user_id', $request->user_id)
            ->where('product_id', $request->product_id)
            ->first();

        if($cart_item){
            $cart_item->delete();
            return response()->json([
                'message' => 'Item removed from cart'
            ],  Response::HTTP_OK);
        }

        return response()->json([
            'message' => 'Item not found in cart'
        ],  Response::HTTP_NOT_FOUND);
    }

    public function checkout(CartItemRequest $request): JsonResponse|Response
    {
        $cart_items = CartItem::where('user_id', $request->user_id)->get();

        if($cart_items->count() > 0){
            $order = Order::create([
                'user_id' => $request->user_id
            ]);

            $cart_items->each(function($cart_item) use ($order){
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart_item->product_id
                ]);
            });

            $cart_items->each->delete();
            return response()->json([
                'message' => 'Cart checked out successfully'
            ],  Response::HTTP_OK);
        }

        return response()->json([
            'message' => 'Cart is empty'
        ],  Response::HTTP_NOT_FOUND);
    }
}