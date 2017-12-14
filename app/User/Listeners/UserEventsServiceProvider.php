<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\User\Listeners;

use App\User\Events\UserProfileChanged;
use Cai\Providers\EventServiceProvider;

class UserEventsServiceProvider
{
    protected $listen = [
        UserProfileChanged::class => [
            UserProfileChangedNotification::class,
        ]
    ];

    public function __construct(EventServiceProvider $provider)
    {
        $provider->registerListeners($this->listen);
    }
}