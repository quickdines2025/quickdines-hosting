<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\VendorController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('customer')->group(function () {
    Route::get('/restaurants', [CustomerController::class, 'getRestaurants']);
    Route::post('/order', [CustomerController::class, 'placeOrder']);
});

Route::prefix('driver')->middleware(['auth:sanctum', 'role:driver'])->group(function () {
    Route::get('/orders', [DriverController::class, 'getOrders']);
    Route::post('/location', [DriverController::class, 'updateLocation']);
});

Route::prefix('vendor')->middleware(['auth:sanctum', 'role:vendor'])->group(function () {
    Route::get('/orders', [VendorController::class, 'getOrders']);
    Route::post('/menu', [VendorController::class, 'updateMenu']);
});

/*
|--------------------------------------------------------------------------
| Razorpay Webhook
|--------------------------------------------------------------------------
|
| Called directly by Razorpay's servers, not by the customer's browser, so
| it is authenticated by its own HMAC signature header, not a logged-in
| session. It is a safety net for payments that were captured but whose
| browser-side verifyPayment() call never completed. See the controller
| for the full explanation and the Razorpay Dashboard setup steps.
*/
Route::post('/webhooks/razorpay', [\App\Http\Controllers\Api\RazorpayWebhookController::class, 'handle'])
    ->name('webhooks.razorpay');
