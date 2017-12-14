<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\Guess\Listeners;

use App\Guess\Events\GuessEvent;

class GuessEventSubscriber
{
    public function handle(GuessEvent $event)
    {
        if ($event->getGuessType() == GuessEvent::GUESS_CAI) {
            $this->handleGuessCai($event);
        } else {
            $this->handleGuessCard($event);
        }
    }

    protected function handleGuessCard($event)
    {
        dd($event);
    }

    protected function handleGuessCai($event)
    {
        dd($event);
    }
}