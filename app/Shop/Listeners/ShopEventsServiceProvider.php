<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\Shop\Listeners;

use App\Shop\Events\ShopProfileChanged;
use Cai\Providers\EventServiceProvider;

class ShopEventsServiceProvider
{
    protected $listen = [
        ShopProfileChanged::class => [
            ShopProfileChangedNotification::class,
        ]
    ];

    public function __construct(EventServiceProvider $provider)
    {
        $provider->registerListeners($this->listen);
    }
}