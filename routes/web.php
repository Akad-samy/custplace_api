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

use App\Http\Controllers\UserController;

$router->get('/', function () use ($router) {
    // return $router->app->version();
    return "hey there it's me";
});
//Product Routes:
$router->get('products', 'ProductController@index');
//Review Routes
$router->post('reviews/{product_codebar}', ['uses' => 'ReviewController@store']); 
$router->get('reviews/{product_codebar}',  ['uses' => 'ReviewController@index']);

