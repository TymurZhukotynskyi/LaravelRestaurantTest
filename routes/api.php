<?php

use App\Http\Controllers\Booking\BookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('api')->group(function () {
    Route::post('/create-booking', [BookingController::class, 'createBooking']);
    Route::get('/bookings', [BookingController::class, 'getBookings']);
    Route::delete('/cancel-booking/{id}', [BookingController::class, 'cancel']);
});
