<?php

namespace Pine\App;

class ErrorHandler
{
    public static function handleException(\Throwable $exception) {
        $errorMessage = $exception->getMessage();
        $errorFile = $exception->getFile();
        $lineNumber = $exception->getLine();
        $errorTrace  = $exception->getTraceAsString();
        if (isset($exception->viewFile)) {
            if (Util::env("APP_DEBUG")) {
                return new View("errors/debug", compact('errorMessage', 'errorFile', 'lineNumber', 'errorTrace'));
            }

            return new View($exception->viewFile);
        }

        dump([$errorMessage, $errorFile, $lineNumber, $errorTrace]);
    }
}