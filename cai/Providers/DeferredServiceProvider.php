<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace Cai\Providers;

use Cai\Extend\IP;
use Cai\Extend\Storage;
use Cai\Foundation\ServiceProvider;
use Illuminate\Broadcasting\BroadcastServiceProvider;
use OSS\OssClient;

//use SocialiteProviders\Manager\ServiceProvider as SocialiteProviders;

use SocialiteProviders\Manager\ServiceProvider as SocialiteProviders;

class DeferredServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->registerMailService();
        $this->registerSMSService();
        $this->registerStorageService();
        $this->registerBroadcastService();
        $this->registerIpService();

//        if ($this->app->environment() === 'local') {
//            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
//        }
    }

    protected function registerStorageService()
    {
        $this->app->singleton('storage', function () {
            $storageConfig = $this->loadConfig('storage');

            $client = new OssClient(
                $storageConfig['app_id'],
                $storageConfig['secret_token'],
                $storageConfig['endpoint']
            );

            return new Storage($client);
        });
    }

    protected function registerIpService()
    {
        $this->app->singleton('ip', function () {
            require_once base_path() . '/lib/ip2region-1.3/binding/php/Ip2Region.class.php';

            $ip2Region = new \Ip2Region(base_path() . '/lib/ip2region-1.3/data/ip2region.db');

            return new IP($ip2Region);
        });
    }

    protected function registerMailService()
    {
        $this->app->configure('mail');
    }

    protected function registerSMSService()
    {
        $this->app->configure('sms');
    }

    protected function registerSocialiteService()
    {
        $this->app->configure('social');
        $this->app->register(SocialiteProviders::class);
    }

    protected function registerBroadcastService()
    {
        $this->app->configure('broadcasting');
        $this->app->register(BroadcastServiceProvider::class);
    }
}