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

Route::group(['prefix' => 'articles', 'middleware' => 'throttle:10000000'], function () {
	Route::post('/{articleId}/rate', 'RatingController@rate');
	Route::post('/', 'ArticleController@create');
	Route::get('/', 'ArticleController@get');
	Route::get('/{articleId}', 'ArticleController@show');
});
