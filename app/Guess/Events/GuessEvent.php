<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\Guess\Events;

class GuessEvent
{
    const GUESS_CAI = 1;
    const GUESS_CARD = 2;

    protected $guessId;

    protected $uid;

    protected $guessType;

    protected $option;

    protected $optionDesc;

    public function __construct($guessType, $guessId, $uid, $option, $optionDesc)
    {
        $this->guessId = $guessId;
        $this->uid = $uid;
        $this->guessType = $guessType;
        $this->option = $option;
        $this->optionDesc = $optionDesc;
    }

    /**
     * @return mixed
     */
    public function getGuessId()
    {
        return $this->guessId;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @return mixed
     */
    public function getGuessType()
    {
        return $this->guessType;
    }

    /**
     * @return mixed
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * @return mixed
     */
    public function getOptionDesc()
    {
        return $this->optionDesc;
    }

}