<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Article;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowArticleTest extends TestCase
{
    /**
     * Test if an article is available.
     *
     * @return void
     */
    public function testAvailableArticle(): void
    {
        $article = Article::create([
            'title' => 'Test title',
            'body' => 'Test body',
        ]);

        $response = $this->get("/api/articles/$article->id");

        $response->assertStatus(200)
            ->assertSee('id')
            ->assertSee('title')
            ->assertSee('body')
            ->assertSee('created_at')
            ->assertSee('updated_at')
            ->assertSee('deleted_at');

        Article::find($article->id)->forceDelete();
    }

    /**
     * Test if an article is unavailable.
     *
     * @return void
     */
    public function testUnavailableArticle(): void
    {
        $response = $this->get('/api/articles/11111111111');

        $response->assertStatus(500);
    }
}
