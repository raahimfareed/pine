<?php

namespace Pine\App;

class Util {
    public static function isJson($json) {
        json_decode($json);
        return json_last_error() == JSON_ERROR_NONE;
    }
}
