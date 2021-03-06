<?php

namespace App\Models;

use App\Models\Article;
use App\Jobs\ArticleRatingJob;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArticleRating extends Model
{
    use SoftDeletes, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'article_id',
        'score',
        'ip_address',
    ];

    /**
     * Boot method will run before any model event.
     *
     * @return void
     */
    protected static function booted(): void
    {
        self::created(function (ArticleRating $articleRating) {
            dispatch(new ArticleRatingJob($articleRating->article));
        });
    }


    /**
     * Get the article related to the rating.
     *
     * @return object
     */
    public function article(): object
    {
        return $this->belongsTo(Article::class);
    }
}
