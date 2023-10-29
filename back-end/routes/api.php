<?php

use App\Http\Controllers\Api\FrontEnd\Admin\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FrontEnd\Auth\LoginController;
use App\Http\Controllers\Api\FrontEnd\Order\OrderController;
use App\Http\Controllers\Api\FrontEnd\Auth\RegisterController;
use App\Http\Controllers\Api\frontend\Order\OrderItemController;
use App\Http\Controllers\Api\FrontEnd\Product\ProductController;
use App\Http\Controllers\Api\FrontEnd\Category\CategoryController;
use App\Http\Controllers\Api\FrontEnd\Permission\PermissionController;
use App\Http\Controllers\Api\FrontEnd\Role\RoleController;

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
    //Roles
    Route::get('/roles', [RoleController::class, 'index']);
    //Permissions
    Route::put('/permissions/{id}', [PermissionController::class, 'update']);
    Route::get('/permissions', [PermissionController::class, 'index']);

    //User
    Route::apiResource('users', UserController::class);

    //Categories
    Route::post('/categories/multi/delete', [CategoryController::class, 'destroyMulti']);
    Route::apiResource('categories', CategoryController::class);

    //Products
    Route::post('/products/multi/delete', [ProductController::class, 'destroyMulti']);
    Route::apiResource('products', ProductController::class);

    //Order
    Route::get('/orders', [OrderController::class, 'index']);
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']);
    Route::post('/orders', [OrderItemController::class, 'store']);
    Route::put('/orders/{id}', [OrderItemController::class, 'update']);
});
