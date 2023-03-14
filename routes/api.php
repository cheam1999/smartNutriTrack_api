<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarcodeProductsController;

Route::get('/get_barcode_products/{code}', [BarcodeProductsController::class, 'show']);
Route::get('/get_all', [BarcodeProductsController::class, 'index']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
