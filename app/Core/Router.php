<?php

namespace App\Core;

class Router
{
    private $routes = [];

    public function get($path, $handler)
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post($path, $handler)
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch($uri, $method, $db = null)
    {
        // Clean URI (remove query string)
        $uri = parse_url($uri, PHP_URL_PATH);
        
        // Make basePath dynamic from BASE_URL
        $urlPath = parse_url(BASE_URL, PHP_URL_PATH);
        if ($urlPath && strpos($uri, $urlPath) === 0) {
            $uri = substr($uri, strlen($urlPath));
        }
        
        if ($uri == '' || $uri == '/') $uri = '/';

        if (!isset($this->routes[$method][$uri])) {
            header("HTTP/1.0 404 Not Found");
            echo "404 Not Found: " . $uri;
            return;
        }

        $handler = $this->routes[$method][$uri];

        if (is_string($handler)) {
            list($controllerName, $methodName) = explode('@', $handler);
            $controllerClass = "App\\Controllers\\" . $controllerName;
            
            // Require the controller file
            $controllerFile = BASE_PATH . 'app/Controllers/' . $controllerName . '.php';
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
            } else {
                $controllerFile = BASE_PATH . 'app/controllers/' . $controllerName . '.php';
                if (file_exists($controllerFile)) {
                    require_once $controllerFile;
                }
            }

            if (class_exists($controllerClass)) {
                $controller = new $controllerClass($db);
                if (method_exists($controller, $methodName)) {
                    $controller->$methodName();
                } else {
                    echo "Method $methodName not found in $controllerClass";
                }
            } else {
                echo "Controller class $controllerClass not found";
            }
        } elseif (is_callable($handler)) {
            $handler();
        }
    }
}
