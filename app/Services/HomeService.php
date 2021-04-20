<?php

namespace App\Services;

use App\Jobs\DatabaseSeedJob;

class HomeService
{
    /**
     * Check if an ip address has viewed an article.
     *
     * @return array
     */
    public function index(): array
    {
        DatabaseSeedJob::dispatch();

        return ['message' => 'Welcome to home page of knowledge center.'];
    }
}
