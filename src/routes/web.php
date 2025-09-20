<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;


Route::get('/', [ItemController::class, 'index']);
Route::get('/item/{item_id}', [ItemController::class, 'show']);

Route::middleware('auth',)->group(function () {
    Route::get('/setting', [UserController::class, 'settingProfile']);
    Route::post('/setting', [UserController::class, 'storeProfile']);
    Route::get('/mypage/profile', [UserController::class, 'edit']);
    Route::post('/mypage/profile', [UserController::class, 'editProfile']);
    Route::get('/mypage', [UserController::class, 'profile']);
    Route::post('/item/{item}/comment', [ItemController::class, 'postComment']);
    Route::post('item/{item_id}/favorite', [ItemController::class, 'toggle']);
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'purchaseForm']);
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress']);
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress']);
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'purchase']);
    Route::get('/sell', [ItemController::class, 'create']);
    Route::post('/sell', [ItemController::class, 'store']);
});
