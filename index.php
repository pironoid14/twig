<?php
// Minimal PHP front controller for Twig templates using Router class
// require_once __DIR__.'/vendor/autoload.php'; // Temporarily commented out for testing
require_once __DIR__.'/Router.php';

// $loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/templates');
// $twig = new \Twig\Environment($loader);

class MockTwig {
    public function render($template) {
        return "<html><head><title>Test</title></head><body><h1>Template: $template</h1><p>This is a mock rendering for testing.</p></body></html>";
    }
}
$twig = new MockTwig();

$router = new Router($twig);
$router->addRoute('/', 'landing.twig');
$router->addRoute('/index.php', 'landing.twig');
$router->addRoute('/auth', 'auth.twig');
$router->addRoute('/dashboard', 'dashboard.twig');
$router->addRoute('/tickets', 'tickets.twig');

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($path);
