<?php

use Laravel\Lumen\Routing\Router;

$app->router->group([
    'namespace' => 'App\Shop\Http\Controllers',
    'prefix' => 'shop',
    //'middleware' => 'jwt',
], function (Router $router) {
    $router->get('product', 'ProductController@product');
    $router->get('test', 'ProductController@test');
    $router->post('package', 'PackageController@package');
    $router->post('debris', 'DebrisController@debris');
    $router->post('debrisLogs', 'DebrisController@debrisLogs');
    $router->post('purchaseLogs', 'ProductController@purchaseLogs');
    $router->post('exChangeDebris', 'DebrisController@exChangeDebris');



});
