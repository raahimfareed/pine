<?php

namespace Pine\App;

class ErrorHandler
{
    public static function handleException(\Throwable $exception) {
        $errorMessage = $exception->getMessage();
        $errorFile = $exception->getFile();
        $lineNumber = $exception->getLine();
        $errorTrace  = $exception->getTraceAsString();
        $wantsJson = self::wantsJson();

        if ($wantsJson) {
            $response = new Response();
            $status = 200;
            if (method_exists($exception, 'getStatusCode')) {
                $status = $exception->getStatusCode();
                $response->status = $status;
                if (Util::env("APP_DEBUG")) {
                    $response->body = ["message" => $errorMessage, "file" => $errorFile, "line" => $lineNumber, "trace" => $errorTrace];
                } else {
                    $response->body = ["message" => $errorMessage];
                }
                $result = $response->json();

                if ($response->isJson()) {
                    header("Content-Type: application/json; charset=utf-8");
                }
                http_response_code($result->status);
                echo $result->body;
                return;
            }

        }

        if (method_exists($exception, 'getViewFile') && $exception->getViewFile()) {
            $viewFile = $exception->getViewFile();
            if (Util::env("APP_DEBUG")) {
                return new View("errors/debug", compact('errorMessage', 'errorFile', 'lineNumber', 'errorTrace'));
            }

            return new View($viewFile);
        }

        dump(["message" => $errorMessage, "file" => $errorFile, "line" => $lineNumber, "trace" => $errorTrace]);
    }

    public static function wantsJson(): bool
    {
        $acceptHeader = $_SERVER['HTTP_ACCEPT'] ?? '';

        return str_contains($acceptHeader, 'application/json');
    }
}
