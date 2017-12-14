<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\Guess\Events;

class GuessCard extends GuessEvent
{
    protected $cards;

    public function __construct($guessId, $uid, $option, $optionDesc, $cards)
    {
        parent::__construct(self::GUESS_CARD, $guessId, $uid, $option, $optionDesc);

        $this->cards = $cards;
    }

    /**
     * @return mixed
     */
    public function getCards()
    {
        return $this->cards;
    }
}