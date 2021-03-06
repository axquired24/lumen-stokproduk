<?php

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

$router->get('/key', function () {
    return str_random(32);
});

$router->post('/productcat[/{action}]', 'HomeController@resProductCat');
$router->post('/product/search', 'HomeController@resProductSearch');
$router->post('/product[/{action}]', 'HomeController@resProduct');
$router->post('/dummy/product', 'HomeController@dummyProduct');