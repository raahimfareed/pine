<?php

namespace Pine\Models;

use Pine\App\BaseModel;

class User extends BaseModel {
    public static string $_table = "users";
    public static string $_primaryKey = "id";
    public static array $_attributes = [];

    public string $name;
    public string $email;
    private string $password;

    public static function initialize() {
        self::setup()
            ->addColumn('name', "VARCHAR(255)")->nullable('name', false)
            ->addColumn('email', "VARCHAR(255)")->nullable('email', false)
            ->addColumn('password', "VARCHAR(255)")->nullable('password', false);
    }
}
