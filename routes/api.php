<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1', 'namespace' => 'Api'], function(){
    Route::resource('products', 'ProductsController', ['only' => ['index', 'store', 'show', 'update']]);
    Route::resource('categories', 'CategoriesController', ['only' => ['index', 'store', 'show', 'update']]);
    Route::resource('shopping_carts', 'ShoppingCartsController', ['only' => ['store', 'destroy']]);
    Route::resource('users', 'UsersController', ['only' => ['index', 'store']]);
    Route::post('shopping_cart/pay/{id}', 'ShoppingCartsController@pay')->name('shopping_cart.pay');
});
