<?php

use Illuminate\Http\Response;
use App\Jobs\DatabaseSeedJob;
use App\Http\Controllers\Controller;
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
	DatabaseSeedJob::dispatch();
    return (new Controller)->setResponse(Controller::SUCCESS, 'Welcome to home page of knowledge center.', Response::HTTP_OK);
})->middleware(['throttle:10000000']);
