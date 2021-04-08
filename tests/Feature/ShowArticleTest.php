<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowArticleTest extends TestCase
{
    /**
     * Test if an article is available.
     *
     * @return void
     */
    public function testAvailableArticle()
    {
        $response = $this->get('/api/articles/1');

        $response->assertStatus(200)
            ->assertSee('id')
            ->assertSee('title')
            ->assertSee('body')
            ->assertSee('created_at')
            ->assertSee('updated_at')
            ->assertSee('deleted_at');
    }

    /**
     * Test if an article is unavailable.
     *
     * @return void
     */
    public function testUnavailableArticle()
    {
        $response = $this->get('/api/articles/11111111111');

        $response->assertStatus(500);
    }
}
