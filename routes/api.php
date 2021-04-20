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

Route::apiResource('articles', ArticleController::class)
	->middleware('throttle:10000000')
    ->only(['index', 'store', 'show']);

Route::apiResource('ratings', RatingController::class)
	->middleware('throttle:10000000')
    ->only(['store']);
