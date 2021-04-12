<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    /**
     * Test initial setup.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->runDatabaseMigrations();
    }
}
