<?php
/**
 *
 * Copyright (c) 2017.  收菜网
 * Date: 2017/10/23
 * Time: 18:01
 */
namespace App\Admin\Providers;

use App\Admin\Console\Commands\SpiderCommand;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerCommands();
        $this->app->configure('admin_menu');
    }

    public function registerCommands()
    {
        $this->app->bindIf('command.spider',function(){
            return new SpiderCommand();
        });
        $this->commands(['command.spider']);
    }
}