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
    return "Api Aplikasi SIPOYAN with " . $router->app->version() . "\n DISCLAIMER";
});

$router->get('keygenerate', function () {
    return illuminate\support\Str::random(32);
});

$router->get('/response', ['uses' => 'ResponseController@response2']);

// $router->post('/register', ['uses' => 'AuthController@register']);
// $router->post('/login', ['uses' => 'AuthController@login']);

$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
});
