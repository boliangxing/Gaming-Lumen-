<?php

use Laravel\Lumen\Routing\Router;

$app->router->group([
    'namespace' => 'App\Guess\Http\Controllers',
    'middleware' => 'jwt',
], function (Router $router) {
    $router->post('guess/cai', 'GuessController@guessCai');
    $router->post('guess/card', 'GuessController@guessCard');
    
});