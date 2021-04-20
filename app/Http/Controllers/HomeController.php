<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HomeService;
use App\Http\Resources\HomeResource;

class HomeController extends Controller
{
    /**
     * Default message to show home page.
     *
     * @param \App\Services\HomeService $service
     *
     * @see \App\Services\HomeService::index()
     *
     * @return object
     */
    public function index(HomeService $service): object
    {
        $data = $service->index();

        return new HomeResource($data);
    }
}
