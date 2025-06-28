<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Membuat aplikasi Lumen
$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

// Load environment variables
(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

// Set default timezone
date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));

/*
|--------------------------------------------------------------------------|
| Register Middleware                                                       |
|--------------------------------------------------------------------------|
|
| Registering the application's middleware. We'll include global middleware|
| that runs before and after each request, as well as middleware assigned   |
| to specific routes.                                                       |
|
 */

$app->middleware([
    App\Http\Middleware\CorsMiddleware::class, // Tambahkan middleware untuk CORS jika diperlukan
]);

$app->routeMiddleware([
    'auth' => App\Http\Middleware\Authenticate::class,
]);

/*
|--------------------------------------------------------------------------|
| Register Service Providers                                                 |
|--------------------------------------------------------------------------|
|
| Registering all application service providers, including JWTAuth service|
| provider.                                                                  |
|
 */

$app->register(App\Providers\AppServiceProvider::class);
$app->register(Illuminate\Pagination\PaginationServiceProvider::class);
$app->register(Illuminate\Filesystem\FilesystemServiceProvider::class);
$app->register(App\Providers\AuthServiceProvider::class);
$app->register(Illuminate\Cache\CacheServiceProvider::class);
$app->register(Illuminate\Routing\RoutingServiceProvider::class);

// Add the JWTAuth Service Provider for JWT authentication
$app->register(Tymon\JWTAuth\Providers\LumenServiceProvider::class);

/*
|--------------------------------------------------------------------------|
| Configure JWT                                                             |
|--------------------------------------------------------------------------|
|
| Load the configuration for JWT                                              |
|
 */

$app->configure('jwt'); // Pastikan file jwt.php ada di folder config

/*
|--------------------------------------------------------------------------|
| Create The Application                                                     |
|--------------------------------------------------------------------------|
|
| Here we will load the environment and create the application instance     |
| that serves as the central piece of this framework. We'll use this        |
| application as an "IoC" container and router for this framework.           |
|
 */

$app->withFacades();
$app->withEloquent();

// Jika Anda membutuhkan ResponseServiceProvider, tambahkan baris berikut:
// $app->register(Illuminate\Response\ResponseServiceProvider::class);

/*
|--------------------------------------------------------------------------|
| Register Container Bindings                                                |
|--------------------------------------------------------------------------|
|
| Registering the exception handler and console kernel, which are essential|
| for the application.                                                       |
|
 */

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------|
| Load The Application Routes                                               |
|--------------------------------------------------------------------------|
|
| Include the routes file to add all of the application's URLs and their   |
| corresponding controllers.                                                 |
|
 */

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__ . '/../routes/web.php';
});

// Tambahkan route middleware auth untuk memastikan perlindungan pada rute yang membutuhkan autentikasi
$app->routeMiddleware([
    'auth' => App\Http\Middleware\Authenticate::class,
]);

$app->middleware([
    \App\Http\Middleware\CorsMiddleware::class,
]);
return $app;
