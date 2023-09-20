<?php

namespace Pine\App\Migrations;

return new class {
    public function schema() {
        return [
            "id" => "INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL",
            "name" => "VARCHAR(255) NOT NULL",
            "email" => "VARCHAR(255) NOT NULL",
            "password" => "VARCHAR(255) NOT NULL",
        ];
    }
};
