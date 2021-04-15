<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Article;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowArticleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if an article is available.
     *
     * @return void
     */
    public function testAvailableArticle(): void
    {
        $article = Article::factory()->create();

        $response = $this->get("/api/articles/$article->id");

        $response->assertStatus(Response::HTTP_OK)
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
    public function testUnavailableArticle(): void
    {
        $response = $this->get('/api/articles/11111111111');

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
