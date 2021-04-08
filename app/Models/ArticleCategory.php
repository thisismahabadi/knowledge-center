<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArticleCategory extends Model
{
    use SoftDeletes, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'article_id',
        'category_id',
    ];

    /**
     * Assign categories to an article.
     *
     * @param array $categories
     * @param int $articleId
     *
     * @return void
     */
    public function assignCategory(array $categories, int $articleId): void
    {
        if ($categories) {
            foreach ($categories as $category) {
                self::create([
                    'article_id' => $articleId,
                    'category_id' => $category,
                ]);
            }
        }
    }
}
