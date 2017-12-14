<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace Cai\Providers;

use Illuminate\Redis\RedisServiceProvider;
use Illuminate\Support\ServiceProvider;
use Cai\Providers\EventServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerRedisService();
        $this->registerRedisCache();
        $this->registerEventService();
    }

    protected function registerRedisService()
    {
        $this->app->configure('database');
        $this->app->register(RedisServiceProvider::class);
    }

    protected function registerRedisCache()
    {
        $this->app->configure('cache');
    }

    protected function registerEventService()
    {
        //$this->app->configure('database');
        $this->app->register(EventServiceProvider::class);
    }

}