<?php

require_once __DIR__.'/../vendor/autoload.php';

const DS = DIRECTORY_SEPARATOR;

define('SC_START_TIME', date('Y-m-d H:i:s'));

try {
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);

$app->withFacades();

class_alias(Laravel\Socialite\Facades\Socialite::class, 'Socialite');
 $app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    \Cai\Exceptions\Handler\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    \App\Common\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

// $app->middleware([
//    App\Http\Middleware\ExampleMiddleware::class
// ]);

$routeMiddles = [
    'jwt' => \Cai\Middleware\JWTMiddleware::class,
    'admin' => \Cai\Middleware\AdminJWTMiddleware::class,
    'admin_log' => \Cai\Middleware\AdminRequestLogMiddleware::class,
];

$app->routeMiddleware($routeMiddles);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->register(\Cai\Providers\AppServiceProvider::class);
$app->register(\Cai\Providers\AuthServiceProvider::class);
$app->register(\Cai\Providers\DeferredServiceProvider::class);
$app->register(\Illuminate\Mail\MailServiceProvider::class);
$app->register(\App\Admin\Providers\AdminServiceProvider::class);

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

require __DIR__.'/../routes/web.php';
require "helpers.php";

return $app;
