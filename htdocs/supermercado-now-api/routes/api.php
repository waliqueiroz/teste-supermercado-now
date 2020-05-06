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

// ROTAS DE AUTENTICACAO
Route::post('/login', 'Auth\LoginController@login');
Route::post('/login/refresh', 'Auth\LoginController@refresh');
Route::middleware('auth:api')->get('/logout', 'Auth\LoginController@logout');

// USUARIO AUTENTICADO
Route::middleware('auth:api')->get('user', 'Api\UserController@getCurrentUser');

// CRUD PRODUTOS
Route::middleware('auth:api')->prefix('/products')->group(function () {
    Route::middleware('permission:product.index')->get('', 'Api\ProductController@index');
    Route::middleware('permission:product.store')->post('', 'Api\ProductController@store');
    Route::middleware('permission:product.show')->get('/{id}', 'Api\ProductController@show');
    Route::middleware('permission:product.update')->put('/{id}', 'Api\ProductController@update');
    Route::middleware('permission:product.updateStatus')->put('/{id}/update-status', 'Api\ProductController@updateStatus');
    Route::middleware('permission:product.destroy')->delete('/{id}', 'Api\ProductController@destroy');
});

// STATUS
Route::middleware('auth:api')->prefix('/statuses')->group(function () {
    Route::middleware('permission:status.index')->get('', 'Api\StatusController@index');
});

// CRUD USUÁRIOS
Route::middleware('auth:api')->prefix('/users')->group(function () {
    Route::middleware('permission:user.index')->get('', 'Api\UserController@index');
    Route::middleware('permission:user.store')->post('', 'Api\UserController@store');
    Route::middleware('permission:user.show')->get('/{id}', 'Api\UserController@show');
    Route::middleware('permission:user.update')->put('/{id}', 'Api\UserController@update');
    Route::middleware('permission:user.destroy')->delete('/{id}', 'Api\UserController@destroy');
});
