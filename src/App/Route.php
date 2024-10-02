<?php

namespace Pine\App;

abstract class Route {
    private static $routes = [];
    private function __construct()
    {}

    public static function get(string $path, $fn) {
        $route = self::createRouteKey('GET', $path);
        self::$routes[serialize($route)] = $fn;
    }

    public static function post(string $path, $fn) {
        $route = self::createRouteKey('POST', $path);
        self::$routes[serialize($route)] = $fn;
    }

    public static function delete(string $path, $fn) {
        $route = self::createRouteKey('DELETE', $path);
        self::$routes[serialize($route)] = $fn;
    }

    public static function put(string $path, $fn) {
        $route = self::createRouteKey('PUT', $path);
        self::$routes[serialize($route)] = $fn;
    }

    public static function patch(string $path, $fn) {
        $route = self::createRouteKey('PATCH', $path);
        self::$routes[serialize($route)] = $fn;
    }

    public static function createRouteKey(string $method, string $path) {
        $route = [$method, $path];
        if (strlen($path) === 0) {
            $route[1] = '/';
        }

        if ($path[0] != "/") {
            $route[1] = '/' . $path;
        }

        return $route;
    }

    public static function load() {
        $uri = $_SERVER['REQUEST_URI'];
        $uriArr = explode('?', $uri);
        $uri = $uriArr[0];
        $method = $_SERVER['REQUEST_METHOD'];

        $key = [$method, $uri];

        $route = self::$routes[serialize($key)] ?? null;

        if ($route === null) {
            http_response_code(404);
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
            $result = $class->$method(new Request());
            if (gettype($result) === "object" && get_class($result) === Response::class) {
                if ($result->isJson()) {
                    header("Content-Type: application/json; charset=utf-8");
                }
                http_response_code($result->status);
                echo $result->body;
                return;
            }

            if (gettype($result) === "string") {
                echo $result;
                return;
            }
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
