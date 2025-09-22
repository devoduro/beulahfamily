<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// SMS API routes (using web middleware for session-based auth)
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/sms-balance', [App\Http\Controllers\Api\SmsController::class, 'getBalance']);
    Route::get('/sms-stats', [App\Http\Controllers\Api\SmsController::class, 'getStats']);
});

// Public API routes for attendance
Route::get('/members/search', [App\Http\Controllers\Api\MemberController::class, 'search'])->name('api.members.search');
