#!/usr/bin/env php
<?php

require_once "./vendor/autoload.php";

use Pine\App\Model;
use Pine\App\QueryBuilder;

$model = Model::create(["name" => "Test", "email" => "email@example.com"]);

$case = "up";

if ($argc > 1) {
    $case = $argv[1];
}

switch ($case) {
    case 'up':
    {
        echo "Running migrations\n";

        $migrationFiles = glob(__DIR__ . '/src/Migrations/*.php');
        foreach ($migrationFiles as $migration) {
            $class = require_once $migration;
            if (is_object($class) && method_exists($class, "schema")) {
                $schema = $class->schema();
                $tableName = strtolower(pathinfo($migration, PATHINFO_FILENAME));
                QueryBuilder::createTable($tableName, $schema);
            }
        }
    }
    break;
    case "down":
        {
            echo "Dropping all tables\n";

            $migrationFiles = glob(__DIR__ . '/src/Migrations/*.php');
            foreach ($migrationFiles as $migration) {
                $class = require_once $migration;
                $tableName = strtolower(pathinfo($migration, PATHINFO_FILENAME));
                QueryBuilder::dropTable($tableName);
            }
        }
    break;
    default:
        echo "Invalid migration command\n";
    break;
}


