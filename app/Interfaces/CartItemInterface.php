<?php
declare(strict_types=1);

namespace App\Interfaces;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\CartItemRequest;

interface CartItemInterface
{
    public function add(CartItemRequest $request): JsonResponse|Response;
    public function remove(Request $request): JsonResponse|Response;
    public function checkout(Request $request): JsonResponse|Response;
}