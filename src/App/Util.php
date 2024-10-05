<?php

namespace Pine\App;

class Util {
    public static function isJson($json) {
        json_decode($json);
        return json_last_error() == JSON_ERROR_NONE;
    }

    public static function env($key, $default = null) {
        $value = $_ENV[$key] ?? $default;

        if ($value === null) {
            return $default;
        }

        if ($value === "true") {
            return true;
        }
        if ($value === "false") {
            return false;
        }

        if (is_numeric($value)) {
            return str_contains($value, ".") ? (float) $value : (int) $value;
        }

        return $value;
    }
}
