<?php
// Mulai session
session_start();

// Set timezone ke Asia/Jakarta (WIB)
date_default_timezone_set('Asia/Jakarta');

// Memuat koneksi database
require_once 'config/koneksi.php';
$database = new Database();

// Menentukan controller dan action default
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'auth';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Menentukan file controller
$controllerFile = 'controllers/' . ucfirst($controller) . 'Controller.php';

// Memeriksa apakah file controller ada
if (file_exists($controllerFile)) {
    require_once $controllerFile;

    // Membuat instance controller
    $controllerClass = ucfirst($controller) . 'Controller';
    if (class_exists($controllerClass)) {
        // Cek apakah constructor membutuhkan parameter (menggunakan Reflection)
        $reflection = new ReflectionClass($controllerClass);
        $constructor = $reflection->getConstructor();

        if ($constructor && $constructor->getNumberOfRequiredParameters() > 0) {
            // Constructor butuh parameter, lewatkan koneksi database
            $controllerInstance = new $controllerClass($database->getConnection());
        } else {
            // Constructor tanpa parameter
            $controllerInstance = new $controllerClass();
        }

        // Memeriksa apakah method (action) ada di dalam controller
        if (method_exists($controllerInstance, $action)) {
            $controllerInstance->$action();
        } else {
            // Jika action tidak ditemukan, gunakan action default
            if (method_exists($controllerInstance, 'index')) {
                $controllerInstance->index();
            } else {
                echo "Action not found";
            }
        }
    } else {
        echo "Controller class not found";
    }
} else {
    // Jika controller tidak ditemukan, tampilkan halaman 404
    echo "Controller not found";
}
?>