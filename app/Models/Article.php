<?php

namespace App\Models;

use App\Models\Category;
use App\Models\ArticleView;
use App\Models\ArticleRating;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use SoftDeletes, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'body',
        'view_count',
        'rating',
    ];

    /**
     * Get the views for the article.
     *
     * @return object
     */
    public function articleView(): object
    {
        return $this->hasMany(ArticleView::class);
    }

    /**
     * Get the ratings for the article.
     *
     * @return object
     */
    public function articleRating(): object
    {
        return $this->hasMany(ArticleRating::class);
    }

    /**
     * Get the categories for the article.
     *
     * @return object
     */
    public function categories(): object
    {
        return $this->belongsToMany(Category::class);
    }
}
