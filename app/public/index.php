<?php

use Controllers\UserController;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

error_reporting(E_ALL);
ini_set("display_errors", 1);

require __DIR__ . '/../vendor/autoload.php';

// Create Router instance
$router = new \Bramus\Router\Router();

$router->setNamespace('Controllers');

// routes for the reviews endpoint
$router->get('/reviews', 'ReviewController@getAll');
$router->get('/reviews/(\d+)', 'ReviewController@getReviewsForSelectedGame');
$router->post('/reviews', 'ReviewController@create');
$router->put('/reviews/(\d+)', 'ReviewController@update');
$router->delete('/reviews/(\d+)', 'ReviewController@delete');

// routes for the games endpoint
$router->get('/games', 'GameController@getAll');
$router->get('/games/(\d+)', 'GameController@getSelectedGame');
$router->post('/games', 'GameController@create');
$router->put('/games/(\d+)', 'GameController@update');
$router->delete('/games/(\d+)', 'GameController@delete');

// routes for the users endpoint
$router->post('/users/login', 'UserController@login');
$router->get('/users', 'UserController@getAll');
$router->post('/users/register', 'UserController@register');

// Run it!
$router->run();