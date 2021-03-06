<?php

namespace App\Models;

use App\Models\Article;
use App\Jobs\ArticleViewJob;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArticleView extends Model
{
    use SoftDeletes, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'article_id',
        'ip_address',
    ];

    /**
     * Boot method will run before any model event.
     *
     * @return void
     */
    protected static function booted(): void
    {
        self::created(function (ArticleView $articleView) {
            $article = Article::find($articleView->article_id);

            dispatch(new ArticleViewJob($article));
        });
    }

    /**
     * Get the article related to the view.
     *
     * @return object
     */
    public function article(): object
    {
        return $this->belongsTo(Article::class);
    }
}
