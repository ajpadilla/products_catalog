<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::resource('identificationTypes', 'IdentificationTypeController', ['only' => ['index', 'create', 'edit']]);
Route::resource('users', 'UsersController', ['only' => ['index', 'create', 'edit']]);
