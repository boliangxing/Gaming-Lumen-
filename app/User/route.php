<?php

use Laravel\Lumen\Routing\Router;

$app->router->group([
    'namespace' => 'App\User\Http\Controllers',
], function (Router $router) {
    $router->post('login', 'AuthController@login');
    $router->post('register', 'AuthController@register');
    $router->post('email', 'EmailController@send');
});

$app->router->group([
    'namespace' => 'App\User\Http\Controllers',
    'middleware' => 'jwt',
], function (Router $router) {
    $router->post('updateAvatar', 'UserController@updateAvatar');
    $router->post('updateEmail', 'UserController@updateEmail');
    $router->post('updateBio', 'UserController@updateBio');
    $router->post('updateMobile', 'UserController@updateMobile');
    $router->post('updatePassword', 'UserController@updatePassword');
    $router->post('logout', 'AuthController@logout');
    $router->get('messages', 'MessageController@messages');
    $router->get('loginHistories', 'AuthController@getLoginHistory');
    $router->get('tips', 'TipsController@getTips');
});

$app->router->group([
    'namespace' => 'App\User\Http\Controllers',
], function (Router $router) {
    $router->post('sms/login', 'SmsController@login');
    $router->post('sms/register', 'SmsController@register');
    $router->post('sms/modify_password', 'SmsController@modifyPassword');
    $router->post('sms/check_code', 'SmsController@checkCode');
});

$app->router->group([
    'namespace' => 'App\User\Http\Controllers',
    'prefix' => 'mission'
], function (Router $router) {
    $router->get('task/getList', 'TaskController@getList');
    $router->post('task/getReward', 'TaskController@getReward');
    $router->get('dailyTask/getList', 'DailyTaskController@getList');
    $router->post('dailyTask/getReward', 'DailyTaskController@getReward');
    $router->get('achievement/getList', 'AchievementController@getList');
    $router->post('achievement/getReward', 'AchievementController@getReward');
});

$app->router->group([
    'namespace' => 'App\User\Http\Controllers',
], function (Router $router) {
    $router->post('checkIn', 'CheckInController@checkIn');
    $router->get('getCheckInData', 'CheckInController@getCheckInData');
    $router->post('getCheckInReward', 'CheckInController@getReward');

    $router->get('getGuessData', 'GuessController@getInfo');
});
