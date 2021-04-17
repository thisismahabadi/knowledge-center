<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\DatabaseSeedJob;
use App\Http\Resources\HomeResource;

class HomeController extends Controller
{
    /**
     * Default message to show home page.
     *
     * @return object
     */
    public function index(): object
    {
		DatabaseSeedJob::dispatch();

		$response = ['message' => 'Welcome to home page of knowledge center.'];

        return new HomeResource($response);
    }
}
