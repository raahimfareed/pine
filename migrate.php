#!/usr/bin/env php
<?php

echo "Running migrations\n";

$migrationFiles = glob(__DIR__ . '/src/Migrations/*.php');

foreach ($migrationFiles as $migration) {
    $class = require_once $migration;
    if (is_object($class) && method_exists($class, "schema")) {
        $schema = $class->schema();

        // TODO: Add a query builder
    }

}

