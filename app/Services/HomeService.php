<?php

namespace App\Services;

use App\Repositories\HomeRepository;

class HomeService
{
    /**
     * The repository data object.
     *
     * @var object
     */
    public $repositories;

    /**
     * This will inject dependencies required by home repository.
     *
     * @param \App\Repositories\HomeRepository $repository
     */
    public function __construct(HomeRepository $repository)
    {
        $this->repositories = $repository;
    }

    /**
     * Check if an ip address has viewed an article.
     *
     * @return array
     */
    public function index(): array
    {
        return $this->repositories
            ->index();
    }
}
