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

Route::group(['prefix' => 'articles', 'middleware' => 'throttle:100'], function () {
	Route::post('/{articleId}/rate', 'App\Http\Controllers\ArticleController@rate');
	Route::post('/', 'App\Http\Controllers\ArticleController@create');
	Route::get('/', 'App\Http\Controllers\ArticleController@get');
	Route::get('/{articleId}', 'App\Http\Controllers\ArticleController@show');
});
