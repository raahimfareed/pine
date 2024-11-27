<?php

namespace Pine\App;

use ReflectionClass;
use ReflectionProperty;

abstract class BaseModel {
    protected static array $models = [];

    private function __construct() {}

    public static function create(array $values) {
        $calledClass = static::class;
        $instance = self::instantiate($values);
        $table = $calledClass::$_table;
        $reflection = new ReflectionClass($instance);
        $properties = $reflection->getProperties();
        $attNames = [];
        $attValues = [];
        foreach ($properties as $key => $property) {
            $propertyName = $property->getName();
            $declaringClass = $property->getDeclaringClass()->getName();
            if ($declaringClass !== $calledClass) continue;
            if ($propertyName[0] === '_') continue;
            array_push($attNames, $propertyName);
        }
        foreach ($attNames as $property) {
            if (array_key_exists($property, $values)) {
                array_push($attValues, $values[$property]);
            }
        }
        $questionMarks = array_fill(0, sizeof($attValues), '?');
        $sql = "INSERT INTO $table (". implode(", ", $attNames) .") VALUES (" . implode(", ", $questionMarks) . ");";
        $dbInstance = Database::getInstance();
        $connection = $dbInstance->getConnection();
        $stmt = $connection->prepare($sql);
        foreach ($attValues as $key => $value) {
            $stmt->bindValue($key + 1, $value);
        }

        $status = $stmt->execute();
        // TODO: Do something with the status, throw an exception perhaps
    }

    public function save() {
        $dbInstance = Database::getInstance();
        $connection = $dbInstance->getConnection();
    }

    abstract public static function initialize();

    public static function instantiate(array $values)
    {
        $calledClass = static::class;
        $instance = new $calledClass;
        $reflection = new ReflectionClass($instance);
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            $propertyName = $property->getName();

            if (array_key_exists($propertyName, $values)) {
                if (!$property->isPublic()) {
                    $property->setAccessible(true);
                }

                $property->setValue($instance, $values[$propertyName]);
            }
        }

        return $instance;
    }

    public static function setup() {
        return new static;
    }

    public function addColumn($name, $type, $options = []) {
        $calledClass = static::class;
        $calledClass::$_attributes[$name] = array_merge(['type' => $type], $options);
        return $this;
    }

    public function primary($column) {
        $calledClass = static::class;
        if (isset($calledClass::$_attributes[$column])) {
            $calledClass::$_attributes[$column]['primary'] = true;
        }
        return $this;
    }

    public function autoIncrement($column) {
        $calledClass = static::class;
        if (isset($calledClass::$_attributes[$column])) {
            $calledClass::$_attributes[$column]['autoIncrement'] = true;
        }
        return $this;
    }

    public function nullable($column, $allow = true) {
        $calledClass = static::class;
        if (isset($calledClass::$_attributes[$column])) {
            $calledClass::$_attributes[$column]['nullable'] = $allow;
        }
        return $this;
    }

    public static function createTable() {
        $columnSql = [];
        $calledClass = static::class;
        foreach ($calledClass::$_attributes as $name => $options) {
            $column = "$name {$options['type']}";
            if (!empty($options['autoIncrement'])) $column .= " AUTO_INCREMENT";
            if (!empty($options['primary'])) $column .= " PRIMARY KEY";
            if (isset($options['nullable']) && !$options['nullable']) $column .= " NOT NULL";
            $columnSql[] = $column;
        }

        $sql = "CREATE TABLE IF NOT EXISTS " . $calledClass::$_table . "(" . implode(", ", $columnSql) . ");";
        return $sql;
    }

    public static function sync() {
        $className = static::class;
        $className::initialize();
        array_push(static::$models, $className);
    }

    public static function getModels() {
        return static::$models;
    }

        public function getProperty(string $name) {
        if (property_exists($this, $name)) {
            $reflection = new ReflectionProperty($this, $name);
            if (!$reflection->isPublic()) {
                $reflection->setAccessible(true);
            }

            return $reflection->getValue($this);
        }
    }

    public function setProperty(string $name, $value) {
        if (property_exists($this, $name)) {
            $reflection = new ReflectionProperty($this, $name);
            if (!$reflection->isPublic()) {
                $reflection->setAccessible(true);
            }

            return $reflection->setValue($this, $value);
        }
    }

}
