<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartItemController;
use App\Services\KafkaConsumerService as Consumer;
use App\Services\KafkaProducerService as Producer;
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
    Route::post('/add-items', [CartItemController::class, 'add']);
    Route::delete('/remove-items', [CartItemController::class, 'remove']);
    Route::post('/checkout', [CartItemController::class, 'checkout']);
});

Route::get('/consume', function (Consumer $consumer) {
    $consumer->consume();
    return 'Consuming messages from Kafka...';
});

Route::get('/produce', function (Producer $producer) {
    $producer->produce('Hello Kafka!');
    return 'Producing message to Kafka...';
});
