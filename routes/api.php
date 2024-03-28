<?php
use App\Http\Controllers\AuthController; 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// User routes

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [UserController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
 
// Wallet routes
Route::post('/wallets', [WalletController::class, 'createwallet']);
Route::post('/wallets/add-balance', [WalletController::class, 'addBalance']);

// Route::put('/wallets/{id}', [WalletController::class, 'update']);

// Transaction routes
Route::post('/transactions', [TransactionController::class, 'createtransaction']);
Route::get('/transactionstory', [TransactionController::class, 'getUserTransactions']); 
});


// Transaction routes
Route::get('/transactions/{id}', [TransactionController::class, 'show']);
// Admin routes (assuming authentication middleware for admin)
Route::get('/transactions', [TransactionController::class, 'index']); // Admin only
Route::delete('/transactions/{id}', [TransactionController::class, 'delete']); // Admin only