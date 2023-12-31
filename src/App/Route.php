<?php

namespace Pine\App;

abstract class Route {
    private static $routes = [];
    private function __construct()
    {}

    public static function get(string $path, $fn) {
        $route = ['GET', $path];
        if (strlen($path) === 0) {
            self::$routes[serialize($route)] = $fn;
            return;
        }
        if ($path[0] != "/") {
            self::$routes[serialize($route)] = $fn;
            return;
        }

        self::$routes[serialize($route)] = $fn;
    }

    public static function post(string $path, $fn) {
        $route = ['POST', $path];
        if (strlen($path) === 0) {
            self::$routes[serialize($route)] = $fn;
            return;
        }
        if ($path[0] != "/") {
            self::$routes[serialize($route)] = $fn;
            return;
        }

        self::$routes[serialize($route)] = $fn;
    }

    public static function load() {
        $uri = $_SERVER['REQUEST_URI'];
        $uriArr = explode('?', $uri);
        $uri = $uriArr[0];
        $method = $_SERVER['REQUEST_METHOD'];

        $key = [$method, $uri];

        $route = self::$routes[serialize($key)] ?? null;

        if ($route === null) {
            header("HTTP/1.0 404 Not Found");
            echo "404 Not Found";
            return;
        }

        self::render($route);
    }


    private static function render($to_render) {
        if (is_array($to_render)) {
            if (sizeof($to_render) < 2) {
                // TODO: Proper error handling
                echo "Array needs to have class and method";
                return;
            }

            [$class, $method] = $to_render;

            if (!class_exists($class)) {
                echo "Class $class doesn't exist";
                return;
            }
            if (!method_exists($class, $method)) {
                echo "Method $method doesn't exist in $class";
                return;
            }

            $class = new $class();
            return $class->$method();
        }

        if (is_callable($to_render)) {
            $called = $to_render(new Request);

            if (is_string($called)) {
                echo $called;
                return;
            }

            return;
        }
    }
}
