<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Interfaces\CartInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CartController extends Controller implements CartInterface
{
    public function add(Request $request): JsonResponse|Response
    {
        
    }

    public function remove(Request $request): JsonResponse|Response
    {
        
    }

    public function checkout(Request $request): JsonResponse|Response
    {
        
    }
}