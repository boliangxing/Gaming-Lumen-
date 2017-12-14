<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace Cai\Providers;

use Cai\Foundation\Auth\AdminJWTGuard;
use Cai\Foundation\Auth\JWTGuard;
use Illuminate\Auth\DatabaseUserProvider;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        \Auth::extend('jwt', function () {
            $this->app->configure('auth');

            $table = config('auth.providers.users.table');

            return new JWTGuard(
                new DatabaseUserProvider($this->app['db']->connection('user'), $this->app['hash'], $table),
                $this->app['request']);
        });

        \Auth::extend('admin', function () {
            $table = 'administrator';
            return new AdminJWTGuard(
                new DatabaseUserProvider($this->app['db']->connection('system'), $this->app['hash'], $table),
                $this->app['request']);
        });
    }
}