<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
 */

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    // Rute-rute yang sudah ada
    $router->get('map/all_provinsi', ['uses' => 'ApiController@getAllProvinsi']);
    $router->get('map/all_aset/{id}', ['uses' => 'ApiController@getAllAset']);
    $router->get('map/province-boundary/{id}', ['uses' => 'ApiController@getProvinceBoundary']);
    $router->get('map/aset_gardu_induk/{id}', ['uses' => 'ApiController@getGarduInduk']);
    $router->get('map/aset_pembangkit/{id}', ['uses' => 'ApiController@getPembangkit']);
    $router->get('map/aset_transmisi/{id}', ['uses' => 'ApiController@getTransmisi']);
    $router->get('map/polygon/{id}', ['uses' => 'ApiController@getBatasProvinsi']);

    // Rute login untuk autentikasi
    // routes/web.php
    $router->post('/login', 'AuthController@login');
    $router->post('/logout', 'AuthController@logout');

});
