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

$router->group(['prefix' => 'ina', 'middleware' => 'jwt'], function () use ($router) {
    $router->get('provinsi', 'InaController@allprovinsi');
    $router->get('provinsi/{id}', 'InaController@provinsi');
    $router->get('kotakab', 'InaController@allkotakab');
    $router->get('kotakab/{id}', 'InaController@kotakabbyid');
    $router->get('kotakabbyprovid/{id}', 'InaController@kotakabbyidProvinsi');
    $router->get('kecamatan', 'InaController@kecamatan');
    $router->get('kecbyid/{id}', 'InaController@kecbyid');
    $router->get('kecbykotakabid/{id}', 'InaController@kecbykotakabid');
    $router->get('kelurahan', 'InaController@kelurahan');
    $router->get('kelurahanbyid/{id}', 'InaController@kelurahanbyid');
    $router->get('kelurahanbykecid/{id}', 'InaController@kelurahanbykecid');
});

$router->group(['prefix' => 'dtwarga', 'middleware' => 'jwt'], function () use ($router) {
    $router->get('allwarga[/{limit}/{offset}]', 'WargaController@allwarga');
    $router->get('bayi[/{limit}/{offset}]', 'WargaController@bayi');
    $router->get('batita[/{limit}/{offset}]', 'WargaController@batita');
    $router->get('balita[/{limit}/{offset}]', 'WargaController@balita');
    $router->get('lansia[/{limit}/{offset}]', 'WargaController@lansia');
});
