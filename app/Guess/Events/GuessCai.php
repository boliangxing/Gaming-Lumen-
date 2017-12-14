<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\Guess\Events;

class GuessCai extends GuessEvent
{
    protected $total;

    public function __construct($guessId, $uid, $option, $optionDesc, $total)
    {
        parent::__construct(self::GUESS_CAI, $guessId, $uid, $option, $optionDesc);

        $this->total = $total;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

}