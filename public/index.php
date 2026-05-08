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
$router->get('/portofolio/edit', 'PortofolioController@edit');
$router->post('/portofolio/update', 'PortofolioController@update');
$router->get('/portofolio/delete', 'PortofolioController@delete');
$router->get('/portofolio/deleteImage', 'PortofolioController@deleteImage');
$router->get('/portofolio/setPrimary', 'PortofolioController@setPrimary');

// Banner Routes
$router->get('/banner', 'BannerController@index');
$router->get('/banner/add', 'BannerController@create');
$router->post('/banner/store', 'BannerController@store');
$router->get('/banner/edit', 'BannerController@edit');
$router->post('/banner/update', 'BannerController@update');
$router->get('/banner/delete', 'BannerController@delete');
$router->get('/banner/toggleActive', 'BannerController@toggleActive');

// Bank Routes
$router->get('/bank', 'BankController@index');
$router->get('/bank/add', 'BankController@create');
$router->post('/bank/store', 'BankController@store');
$router->get('/bank/edit', 'BankController@edit');
$router->post('/bank/update', 'BankController@update');
$router->get('/bank/delete', 'BankController@delete');
$router->get('/bank/toggleActive', 'BankController@toggleActive');

// Social Media Routes
$router->get('/social-media', 'SocialMediaController@index');
$router->get('/social-media/add', 'SocialMediaController@create');
$router->post('/social-media/store', 'SocialMediaController@store');
$router->get('/social-media/edit', 'SocialMediaController@edit');
$router->post('/social-media/update', 'SocialMediaController@update');
$router->get('/social-media/delete', 'SocialMediaController@delete');
$router->get('/social-media/toggleActive', 'SocialMediaController@toggleActive');

// Contact Person Routes
$router->get('/contact-person', 'ContactPersonController@index');
$router->get('/contact-person/add', 'ContactPersonController@create');
$router->post('/contact-person/store', 'ContactPersonController@store');
$router->get('/contact-person/edit', 'ContactPersonController@edit');
$router->post('/contact-person/update', 'ContactPersonController@update');
$router->get('/contact-person/delete', 'ContactPersonController@delete');
$router->get('/contact-person/toggleActive', 'ContactPersonController@toggleActive');

// Dispatch the request
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD'], $db);
