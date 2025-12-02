<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/products/{id}', [\App\Http\Controllers\ProductController::class, 'show']);

Route::post('/holds', [\App\Http\Controllers\ReservationsController::class, 'hold']);

Route::post('/order', [\App\Http\Controllers\OrderController::class, 'store']);

Route::post('/webhook', [\App\Http\Controllers\WebhookController::class, 'handle']);