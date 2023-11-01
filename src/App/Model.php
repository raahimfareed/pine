<?php

namespace Pine\App;

use Symfony\Component\String\Inflector\EnglishInflector;

class Model {
    public static string $table = "";

    protected $fillable = [
        "name"
    ];

    public static function getTable()
    {
        if (self::$table === "") {
            self::$table = basename(str_replace("\\", "/", self::class));
            self::$table = (new EnglishInflector)->pluralize(strtolower(self::$table));
        }

        return self::$table;
    }

    public static function create(array $attributes) {
        QueryBuilder::create(self::class, $attributes);
        $instance = new self();

        return $instance;
    }

    public static function find() {}

    public static function where() {}

    public function delete() {}

    public function save() {}
}
