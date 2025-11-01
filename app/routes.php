<?php

use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Helpers\Auth;
use Bramus\Router\Router;

/** @var Router $router */

$router->get('/login', route(AuthController::class, 'login'));
$router->post('/login', route(AuthController::class, 'login'));
$router->post('/logout', route(AuthController::class, 'logout'));

$router->get('/users', route(UserController::class, 'index'));
$router->get('/users/create', route(UserController::class, 'create'));
$router->post('/users/create', route(UserController::class, 'store'));
$router->get('/users/{id}/edit', route(UserController::class, 'edit'));
$router->put('/users/{id}/update', route(UserController::class, 'update'));
$router->delete('/users/{id}/delete', route(UserController::class, 'delete'));


$router->get('/welcome', function() {
    view('welcome', ['user' => Auth::user()]);
});
