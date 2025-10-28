<?php
// Minimal PHP front controller for Twig templates using Router class
require_once __DIR__.'/Router.php';
require_once __DIR__.'/SimpleTwig.php';

$twig = new SimpleTwig(__DIR__.'/templates');

$router = new Router($twig);
$router->addRoute('/', 'landing.twig');
$router->addRoute('/index.php', 'landing.twig');
$router->addRoute('/auth', 'auth.twig');
$router->addRoute('/dashboard', 'dashboard.twig');
$router->addRoute('/tickets', 'tickets.twig');

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($path);
