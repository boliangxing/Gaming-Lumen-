<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\Guess\Listeners;

use App\Guess\Events\GuessCai;
use App\Guess\Events\GuessCard;
use Cai\Providers\EventServiceProvider;

class GuessEventServiceProvider
{
    protected $listen = [
        GuessCard::class => [
            GuessEventSubscriber::class,
        ],
        GuessCai::class => [
            GuessEventSubscriber::class,
        ],
    ];

    public function __construct(EventServiceProvider $provider)
    {
        $provider->registerListeners($this->listen);
    }
}