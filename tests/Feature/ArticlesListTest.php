<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticlesListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Article object id.
     *
     * @var int
     */
    public $article;

    /**
     * Test initial setup.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->article = Article::factory()->create();
    }

    /**
     * Test that articles list are correct.
     *
     * @return void
     */
    public function testArticlesList(): void
    {
        Article::factory()->create();

        $response = $this->get('/api/articles');

        $response->assertStatus(Response::HTTP_OK)
            ->assertSee('id')
            ->assertSee('title')
            ->assertSee('body');
    }

    /**
     * Test that articles list filter by categories are correct.
     *
     * @return void
     */
    public function testArticlesListFilterByRightStructureCategories(): void
    {
        $category = Category::factory()->create();

        $response = $this->get("/api/articles?categories[]=$category->id");

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test that articles list filter by categories are not correct if categories send without array structure.
     *
     * @return void
     */
    public function testArticlesListFilterByFalseStructureCategories(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->get("/api/articles?categories=hello");

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that articles list filter by creation date are correct.
     *
     * @return void
     */
    public function testArticlesListFilterByRightStructureCreationDate(): void
    {
        $response = $this->get('/api/articles?date[start]=2020-01-01');

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test that articles list filter by creation date are not correct if creation date send without array structure.
     *
     * @return void
     */
    public function testArticlesListFilterByFalseStructureCreationDate(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->get('/api/articles?date=2020');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that articles list sort by views are correct.
     *
     * @return void
     */
    public function testArticlesListSortByRightStructureView(): void
    {
        $response = $this->get('/api/articles?sort[type]=view');

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test that articles list sort by view are not correct if view send without string structure.
     *
     * @return void
     */
    public function testArticlesListSortByFalseStructureView(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->get('/api/articles?sort=view');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that articles list sort by views are correct with correct view date.
     *
     * @return void
     */
    public function testArticlesListSortByRightStructureViewWithCorrectViewDate(): void
    {
        $response = $this->get('/api/articles?sort=view&sort[view_date]=2020-01-01');

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test that articles list sort by popularity are correct.
     *
     * @return void
     */
    public function testArticlesListPopularityByRightStructureView(): void
    {
        $response = $this->get('/api/articles?sort[type]=popularity');

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test that articles list limit are correct.
     *
     * @return void
     */
    public function testArticlesListLimitByRightStructure(): void
    {
        $response = $this->get('/api/articles?limit=10');

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test that articles list limit are not correct if limit send without integer structure.
     *
     * @return void
     */
    public function testArticlesListLimitByFalseStructure(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->get('/api/articles?limit=error');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that articles list search are correct.
     *
     * @return void
     */
    public function testArticlesListSearchByRightStructure(): void
    {
        $response = $this->get('/api/articles?search=A');

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test that articles list search are not correct if search send without string structure.
     *
     * @return void
     */
    public function testArticlesListSearchByFalseStructure(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->get('/api/articles?search[]=1');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
