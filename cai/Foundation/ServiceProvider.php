<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace Cai\Foundation;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    protected function loadConfig($name)
    {
        $configPath = $this->app->getConfigurationPath($name);

        return require $configPath;
    }
}