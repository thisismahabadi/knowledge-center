<?php

namespace App\Models;

use App\Models\Article;
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
     * The rate limit per day.
     *
     * @var int
     */
    CONST RATE_LIMIT_PER_DAY = 10;

    /**
     * Check if an user is eligible to rate today or not.
     *
     * @param $ipAddress
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function isEligible($ipAddress): bool
    {
        $todayRates = self::where('ip_address', $ipAddress)
            ->whereDate('created_at', date('Y-m-d'))
            ->count();

        if ($todayRates >= self::RATE_LIMIT_PER_DAY) {
            throw new \Exception('You just can rate ' . self::RATE_LIMIT_PER_DAY . ' articles per day.');
        }

        return true;
    }

    /**
     * Check if an user has rated to the specified article or not.
     *
     * @param int $articleId
     * @param $ipAddress
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function hasRated(int $articleId, $ipAddress): bool
    {
        $rate = self::where('ip_address', $ipAddress)
            ->where('article_id', $articleId)
            ->count();

        if ($rate) {
            throw new \Exception('You just can rate to an article once.');
        }

        return false;
    }

    /**
     * Rate an article.
     *
     * @param array $data
     *
     * @return object
     */
    public function store(array $data): object
    {
        Article::findOrFail($data['article_id']);

        $this->isEligible($data['ip_address']);
        $this->hasRated($data['article_id'], $data['ip_address']);

        return self::create($data);
    }
}
