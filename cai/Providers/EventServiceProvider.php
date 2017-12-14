<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace Cai\Providers;

use App\User\Listeners\UserEventsServiceProvider;
use Illuminate\Events\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $events;

    public function __construct($app)
    {
        parent::__construct($app);

        $this->events = $app['events'];
    }

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
//        'App\Test\Events\TestEvent' => [
//            'App\Test\Listeners\TestListener',
//        ],
        //三方登录
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            // add your listeners (aka providers) here
            'SocialiteProviders\QQ\QqExtendSocialite@handle',
        ],
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            // add your listeners (aka providers) here
            'SocialiteProviders\Weibo\WeiboExtendSocialite@handle',
        ],
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            // add your listeners (aka providers) here
            'SocialiteProviders\WeixinWeb\WeixinWebExtendSocialite@handle',
        ],
    ];

    protected $subscribe = [
        'App\User\Listeners\TaskSubscriber',
        'App\User\Listeners\AchievementSubscriber',
        'App\User\Listeners\DailyTaskSubscriber',
        'App\User\Listeners\GuessSubscriber',
    ];

    protected $moduleEventServiceProviders = [
        UserEventsServiceProvider::class,
    ];

    public function boot()
    {
        //parent::boot();

        $this->registerListeners($this->listen);

        // 注册其他模块监听的事件
        array_walk($this->moduleEventServiceProviders, function ($eventServiceProvider) {
            new $eventServiceProvider($this);
        });

        $events = app('events');

        foreach ($this->subscribe as $subscriber) {
            $events->subscribe($subscriber);
        }

    }

    public function registerListeners($listen)
    {
        foreach ($listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                $this->app['events']->listen($event, $listener);
            }
        }
    }
}