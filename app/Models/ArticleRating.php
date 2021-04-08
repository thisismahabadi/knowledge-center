<?php

namespace App\Models;

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
        $rate = self::where('ip_address', $ipAddress);
        $todayRates = $rate->whereDate('created_at', date('Y-m-d'))
            ->count();

        if ($todayRates >= 10) {
            throw new \Exception('You just can rate the articles 10 times a day.');
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
}
