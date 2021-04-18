<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Response;
use Database\Factories\ArticleFactory;
use Database\Factories\CategoryFactory;
use Database\Factories\ArticleViewFactory;
use Database\Factories\ArticleRatingFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticlesListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that articles list are correct.
     *
     * @return void
     */
    public function testArticlesList(): void
    {
        $article = Article::factory()->create();

        $response = $this->get('/api/articles');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('data.0.id', $article->id)
            ->assertJsonPath('data.0.title', $article->title)
            ->assertJsonPath('data.0.body', $article->body);
    }

    /**
     * Test that articles list filter by categories are correct.
     *
     * @return void
     */
    public function testArticlesListFilterByRightStructureCategories(): void
    {
        $category = CategoryFactory::new()
            ->has(ArticleFactory::new())
            ->create();

        $response = $this->get("/api/articles?categories[]=$category->id");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1, 'data');
    }

    /**
     * Test that articles list filter by categories are correct with no data.
     *
     * @return void
     */
    public function testArticlesListFilterByRightStructureCategoriesWithNoData(): void
    {
        $response = $this->get("/api/articles?categories[]=1");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(0, 'data');
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
        Article::factory()->create();

        $today = date('Y-m-d');
        $response = $this->get("/api/articles?date[start]=$today");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1, 'data');
    }

    /**
     * Test that articles list filter by creation date are correct with no data.
     *
     * @return void
     */
    public function testArticlesListFilterByRightStructureCreationDateWithNoData(): void
    {
        $today = date('Y-m-d');
        $response = $this->get("/api/articles?date[start]=$today");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(0, 'data');
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
        $articleWithNoView = ArticleFactory::new()
            ->create();

        $articleWithTwoViews = ArticleFactory::new()
            ->has(
                ArticleViewFactory::new()
                    ->count(2)
            )
            ->create();

        $response = $this->get('/api/articles?sort[type]=view');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('data.0.id', $articleWithTwoViews->id)
            ->assertJsonPath('data.1.id', $articleWithNoView->id);
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
        $yesterdayArticleWithAView = ArticleFactory::new()
            ->has(
                ArticleViewFactory::new(
                    ['created_at' => date('Y-m-d', strtotime('-1 days'))]
                )
            )
            ->create();

        $todayArticlesWithAView = ArticleFactory::new()
            ->has(
                ArticleViewFactory::new()
            )
            ->create();

        $today = date('Y-m-d');
        $response = $this->get("/api/articles?sort[type]=view&sort[view_date]=$today");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $todayArticlesWithAView->id);
    }

    /**
     * Test that articles list sort by views are correct with correct view date with no data.
     *
     * @return void
     */
    public function testArticlesListSortByRightStructureViewWithCorrectViewDateWithNoData(): void
    {
        ArticleFactory::new()
            ->has(
                ArticleViewFactory::new(
                    ['created_at' => date('Y-m-d', strtotime('-1 days'))]
                )
            )
            ->create();

        $today = date('Y-m-d');
        $response = $this->get("/api/articles?sort[type]=view&sort[view_date]=$today");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(0, 'data');
    }

    /**
     * Test that articles list sort by popularity are correct.
     *
     * @return void
     */
    public function testArticlesListPopularityByRightStructureView(): void
    {
        $articlesWithFiveScoreAndRatedThreeTime = ArticleFactory::new()
            ->has(
                ArticleRatingFactory::new(['score' => 5])
                    ->count(3)
            )
            ->create();

        $articlesWithFiveScoreAndRatedFiveTime = ArticleFactory::new()
            ->has(
                ArticleRatingFactory::new(['score' => 5])
                    ->count(5)
            )
            ->create();

        $response = $this->get('/api/articles?sort[type]=popularity');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('data.0.id', $articlesWithFiveScoreAndRatedFiveTime->id)
            ->assertJsonPath('data.1.id', $articlesWithFiveScoreAndRatedThreeTime->id);
    }

    /**
     * Test that articles list limit are correct.
     *
     * @return void
     */
    public function testArticlesListLimitByRightStructure(): void
    {
        Article::factory(5)->create();

        $response = $this->get('/api/articles?limit=5');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(5, 'data');
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
        $articles = Article::factory(2)->create();
        $title = $articles[0]->title;

        $response = $this->get("/api/articles?search=$title");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $articles[0]->id)
            ->assertJsonPath('data.0.title', $articles[0]->title)
            ->assertJsonPath('data.0.body', $articles[0]->body);
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
