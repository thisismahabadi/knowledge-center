<?php

namespace App\Repositories;

use App\Jobs\DatabaseSeedJob;

class HomeRepository
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
