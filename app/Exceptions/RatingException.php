<?php

namespace App\Exceptions;

use Exception;

class RatingException extends Exception
{
    /**
     * Throws an error if a user has been rated an article before.
     *
     * @return self
     */
    public static function hasRated(): self
    {
		return new self('You just can rate to an article once.');
    }

    /**
     * Throws an error if a user has requsted to rate articles more than daily limit.
     *
     * @param int $dailyLimit
     *
     * @return self
     */
    public static function dailyLimitExceeded(int $dailyLimit): self
    {
		return new self("You just can rate $dailyLimit articles per day.");
    }
}
