<?php

namespace Pine\App;

use Exception;

require_once __DIR__ . "/../config/bootstrap.php";

abstract class QueryBuilder {
    public static function createTable(string $tableName, array $tableSchema) {
        $sql = "CREATE TABLE `{$tableName}` (";

        $count = 0;
        $sql .= "id INT PRIMARY KEY AUTO_INCREMENT NOT NULL, ";
        foreach ($tableSchema as $attribute => $properties) {
            $sql .= "`{$attribute}` $properties";

            if ($count < sizeof($tableSchema) - 1) {
                $sql .= ", ";
            }

            $count++;
        }
        $sql .= ", created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);";

        $conn = Database::connect();
        try {
            $conn->exec($sql);
        }
        catch (Exception $e) {
            die("Error: " . $e);
            return false;
        }

        return true;
    }

    public static function dropTable(string $tableName) {
        $sql = "DROP TABLE `$tableName`;";
        $conn = Database::connect();
        try {
            $conn->exec($sql);
            echo "Dropped $tableName\n";
        }
        catch (Exception $e) {
            echo "Couldn't drop table $tableName\n";
        }
    }

    public static function create(string $model, array $attributes) {
        $table = $model::getTable();
        $sql = "INSERT INTO `$table`";
        $count = 0;
        $first = "(";
        $second = "(";
        foreach ($attributes as $key => $_) {
            $first .= $key;
            $second .= ":{$key}";

            if ($count < sizeof($attributes) - 1) {
                $first .= ", ";
                $second .= ", ";
            }
            else {
                $first .= ")";
                $second .= ")";
            }

            $count++;
        }

        $sql .= " {$first} VALUES {$second};";

        $conn = Database::connect();
        $stmt = $conn->prepare($sql);
        var_dump($sql);
    }
}
