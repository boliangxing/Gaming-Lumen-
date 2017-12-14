<?php

use Laravel\Lumen\Routing\Router;

$appPath = $app->basePath() . DS . 'app';

require $appPath . DS . 'User' . DS . 'route.php';
require $appPath . DS . 'Guess' . DS . 'route.php';
require $appPath . DS . 'News' . DS . 'route.php';
require $appPath . DS . 'Shop' . DS . 'route.php';
require $appPath . DS . 'Help' . DS . 'route.php';

// @todo: 条件加载
require $appPath . DS . 'Admin' . DS . 'route.php';

$app->router->group([
//    'middleware' => 'jwt',
], function (Router $router) {
    $router->get('/', function () use ($router) {
        return $router->app->version();
    });
});