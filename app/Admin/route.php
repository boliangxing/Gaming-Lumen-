<?php

use Laravel\Lumen\Routing\Router;

$app->router->group([
    'namespace' => 'App\Admin\Http\Controllers',
    'prefix' => 'admin',
    //'middleware' => 'admin|admin_log',
], function (Router $router) {
    $router->get('system/menu','SystemController@getMenu');
    $router->get('system/settings','SystemController@getSettings');
    $router->post('system/settings','SystemController@postSettings');

    $router->get('admin_info','AdministratorController@getInfo');
    $router->get('admin_list','AdministratorController@getList');
    $router->post('add_admin','AdministratorController@addInfo');
    $router->post('update_admin','AdministratorController@updateInfo');
    $router->post('update_password','AdministratorController@updatePassword');

    $router->get('role_list','AdministratorController@getRoleList');
    $router->get('role_info','AdministratorController@getRoleInfo');

    $router->get('crawler',function(){
        return \App\Admin\Logic\DataSpider::crawlerScheduleList();
    });

    $router->get('users', 'UserController@search');
    $router->get('users/info', 'UserController@info');
    $router->post('users/ban', 'UserController@ban');

    $router->get('getBannerLocationList','BannerController@getLocationList');
    $router->post('addBannerLocation','BannerController@addLocation');
    $router->post('updateBannerLocation','BannerController@updateLocation');
    $router->post('deleteBannerLocation','BannerController@deleteLocation');
    $router->get('getBannerList','BannerController@getAll');
    $router->post('getBannerByLocation','BannerController@getByLocation');
    $router->post('addBanner','BannerController@add');
    $router->post('updateBanner','BannerController@update');

    //$router->get('shop/getProductList','ShopController@getProductList');
    $router->get('shop/getExchangeList','ShopController@getExchangeList');
});

$app->router->group([
    'namespace' => 'App\Admin\Http\Controllers',
    'prefix' => 'admin',
    'middleware' => 'admin_log',
], function (Router $router) {
    $router->post('login','AdministratorController@login');
});