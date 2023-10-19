<?php

use App\Http\Controllers\Api\FrontEnd\Auth\LoginController;
use App\Http\Controllers\Api\FrontEnd\Auth\RegisterController;
use App\Http\Controllers\Api\FrontEnd\Category\CategoryController;
use App\Http\Controllers\Api\FrontEnd\Product\ProductController;
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

Route::post('/auth/register', [RegisterController::class, 'store']);
Route::post('/auth/login', [LoginController::class, 'login']);

Route::group(['middleware' => 'checkToken'], function () {
    //Categories
    Route::post('/categories/multi/delete', [CategoryController::class, 'destroyMulti']);
    Route::apiResource('categories', CategoryController::class);

    //Products
    Route::post('/products/multi/delete', [ProductController::class, 'destroyMulti']);
    Route::apiResource('products', ProductController::class);
});
