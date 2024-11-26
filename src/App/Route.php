<?php

namespace Pine\App;

use Pine\Exceptions\RouteNotFound;

abstract class Route {
    private static array $routes = [];
    private function __construct()
    {}

    public static function generateRouteRegexPattern(string $path): string
    {
        $pattern = preg_replace('/:[^\/]+/', '([^\/]+)', $path);
        $pattern = '#^' . $pattern . '$#';

        return $pattern;
    }

    public static function get(string $path, $fn): void
    {
        $route = self::createRouteKey('GET', trim($path));
        self::$routes[serialize($route)] = $fn;
    }

    public static function post(string $path, $fn): void
    {
        $route = self::createRouteKey('POST', trim($path));
        self::$routes[serialize($route)] = $fn;
    }

    public static function delete(string $path, $fn): void
    {
        $route = self::createRouteKey('DELETE', trim($path));
        self::$routes[serialize($route)] = $fn;
    }

    public static function put(string $path, $fn): void
    {
        $route = self::createRouteKey('PUT', trim($path));
        self::$routes[serialize($route)] = $fn;
    }

    public static function patch(string $path, $fn): void
    {
        $route = self::createRouteKey('PATCH', trim($path));
        self::$routes[serialize($route)] = $fn;
    }

    public static function createRouteKey(string $method, string $pathPattern): array
    {
        $route = [$method, $pathPattern];
        if (strlen($pathPattern) === 0) {
            $route[1] = '/';
        }

        if ($pathPattern[0] != "/") {
            $route[1] = '/' . $pathPattern;
        }

        return $route;
    }

    /**
     * @throws RouteNotFound
     */
    public static function load(): void
    {
        $uri = $_SERVER['REQUEST_URI'];
        $uriArr = explode('?', $uri);
        $uri = $uriArr[0];
        $method = $_SERVER['REQUEST_METHOD'];

        foreach (self::$routes as $key => $fn) {
            $key = unserialize($key);
            $path = $key[1];

            if ($method !== $key[0]) continue;

            if ($path === $uri) {
                self::render($fn);
                return;
            }

            $regexPath = self::generateRouteRegexPattern($path);
            if (preg_match($regexPath, $uri, $matches)) {
                array_shift($matches);
                $params = $matches;
                $splitUri = explode(':', $path);
                $paramNames = [];
                foreach ($splitUri as $item) {
                    if (strlen($item) === 0 || $item[0] === '/') continue;
                    $sanitized = explode('/', $item)[0];
                    array_push($paramNames, $sanitized);
                }
                $params = array_combine($paramNames, $params);
                self::render($fn, $params);
                return;
            }


            continue;
        }

        http_response_code(404);
        throw new RouteNotFound($uri);

    }


    private static function render($to_render, ?array $params = null) {
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
            $result = $class->$method(new Request($params));
            if (gettype($result) === "object" && get_class($result) === Response::class) {
                if ($result->isJson()) {
                    header("Content-Type: application/json; charset=utf-8");
                }
                foreach ($result->headers() as $header => $value) {
                    header("$header: $value");
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
            $called = $to_render(new Request($params));

            if (is_string($called)) {
                echo $called;
                return;
            }

            return;
        }
    }
}
