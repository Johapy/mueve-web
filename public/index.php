<?php
session_start(); // Iniciamos sesión PHP aquí para toda la app

// Carga de archivos del Core
require_once "../app/config/config.php";
require_once "../app/core/Router.php";
require_once "../app/core/Controller.php";

// Instancia del Router
$router = new Router();

// --- DEFINICIÓN DE RUTAS ---
// Redirección por defecto (opcional, por ahora mandamos al login)
$router->get('/dashboard', 'DashboardController@index');
$router->get('/login', 'AuthController@loginForm');
$router->get('/register', 'AuthController@registerForm');
$router->get('/', 'HomeController@index');
$router->get('/history', 'DashboardController@history');

$router->post('/login', 'AuthController@login');
$router->post('/register', 'AuthController@register');
$router->post('/api/transactions/create', 'TransactionController@store');
$router->post('/logout', 'AuthController@logout');
// --- DESPACHO ---
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
//--- IGNORE ---

    
?>

