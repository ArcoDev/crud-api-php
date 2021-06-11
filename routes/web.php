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

/*Ejemplo de la creacion de una ruta
  Le pasamos el nombre de la ruta, el nombre del controlador y el de la funcion que contiene la informacion consultada de la BD
*/
$router->get('/libros', 'LibroController@index');

//ENviar datos 
$router->post('/libros', 'LibroController@guardar');