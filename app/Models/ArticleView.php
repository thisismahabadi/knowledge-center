<?php

namespace App\Models;

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
     * Check if an ip address has viewed an article.
     *
     * @param int $articleId
     * @param $ipAddress
     *
     * @return int
     */
    public function hasViewed(int $articleId, $ipAddress): int
    {
        $view = self::where('article_id', $articleId)
            ->where('ip_address', $ipAddress)
            ->count();

        return $view;
    }

    /**
     * Create a log if an ip address has not viewed an article.
     *
     * @param int $articleId
     * @param $ipAddress
     *
     * @return void
     */
    public function logView(int $articleId, $ipAddress): void
    {
        if (! $this->hasViewed($articleId, $ipAddress)) {
            self::create([
                'article_id' => $articleId,
                'ip_address' => $ipAddress,
            ]);
        }
    }
}
