<?php

/**
 * Front Controller
 */

// Start session
session_start();

// Set timezone to Asia/Jakarta (WIB)
date_default_timezone_set('Asia/Jakarta');

// Load App Configuration
require_once dirname(__DIR__) . '/config/app.php';

// Load Database Connection
require_once BASE_PATH . 'config/koneksi.php';
$database = new Database();
$db = $database->getConnection();

// Manual Autoloader
spl_autoload_register(function ($class) {
    $path = str_replace(['App\\', '\\'], ['', '/'], $class);
    $file = BASE_PATH . 'app/' . $path . '.php';

    if (file_exists($file)) {
        require_once $file;
    } else {
        $file = BASE_PATH . 'app/' . strtolower($path) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

use App\Core\Router;

$router = new Router();

// Define Routes
$router->get('/', 'AuthController@index');
$router->get('/login', 'AuthController@index');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@register');
$router->post('/register', 'AuthController@doRegister');
$router->get('/logout', 'AuthController@logout');

$router->get('/dashboard', 'DashboardController@index');
$router->get('/dashboard/admin', 'DashboardController@admin');
$router->get('/dashboard/client', 'DashboardController@client');
$router->get('/dashboard/unauthorized', 'DashboardController@unauthorized');

// Portofolio Routes
$router->get('/portofolio', 'PortofolioController@index');
$router->get('/portofolio/add', 'PortofolioController@create');
$router->post('/portofolio/store', 'PortofolioController@store');

// Banner Routes
$router->get('/banner', 'BannerController@index');
$router->get('/banner/add', 'BannerController@create');
$router->post('/banner/store', 'BannerController@store');

// Bank Routes
$router->get('/bank', 'BankController@index');

// Social Media Routes
$router->get('/social-media', 'SocialMediaController@index');

// Contact Person Routes
$router->get('/contact-person', 'ContactPersonController@index');

// Dispatch the request
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD'], $db);
