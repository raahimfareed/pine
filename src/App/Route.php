<?php

namespace Pine\App;

class Route {
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
            echo "Array <br/>";
            // TODO: Add a way to check arrays
            return;
        }

        if (is_callable($to_render)) {
            $called = $to_render();

            if (is_string($called)) {
                echo $called;
                return;
            }

            echo "Not supported";
            return;
        }
    }
}
