<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Interfaces\CartItemInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Http\Requests\CartItemRequest;
use App\Models\CartItem;

class CartItemController extends Controller implements CartItemInterface
{
    public function add(CartItemRequest $request): JsonResponse|Response
    {
        $cart_item = CartItem::create($request->validated());
        return response()->json($cart_item, 201);
    }

    public function remove(Request $request): JsonResponse|Response
    {
        //remove cart item
    }

    public function checkout(Request $request): JsonResponse|Response
    {
        
    }
}