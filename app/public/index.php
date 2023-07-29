<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

error_reporting(E_ALL);
ini_set("display_errors", 1);

require __DIR__ . '/../vendor/autoload.php';

// Create Router instance
$router = new \Bramus\Router\Router();

$router->setNamespace('Controllers');

// routes for the products endpoint
$router->get('/products', 'ProductController@getAll');
$router->get('/products/(\d+)', 'ProductController@getOne');
$router->post('/products', 'ProductController@create');
$router->put('/products/(\d+)', 'ProductController@update');
$router->delete('/products/(\d+)', 'ProductController@delete');

// routes for the categories endpoint
$router->get('/games', 'GameController@getAll');
$router->get('/games/(\d+)', 'GameController@getOne');
$router->post('/games', 'GameController@create');
$router->put('/games/(\d+)', 'GameController@update');
$router->delete('/games/(\d+)', 'GameController@delete');

// routes for the users endpoint
$router->post('/users/login', 'UserController@login');

// Run it!
$router->run();