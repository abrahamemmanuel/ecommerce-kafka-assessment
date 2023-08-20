<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'cart'], function () {
    Route::post('/add-items', [CartController::class, 'add']);
    Route::delete('/remove-items', [CartController::class, 'remove']);
    Route::post('/checkout', [CartController::class, 'checkout']);
});
