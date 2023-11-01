<?php

namespace Pine\App\Migrations;

return new class {
    public function schema() {
        return [
            "name" => "VARCHAR(255) NOT NULL",
            "email" => "VARCHAR(255) NOT NULL",
            "password" => "VARCHAR(255) NOT NULL",
        ];
    }
};
