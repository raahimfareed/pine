<?php

namespace Pine\App;

use ReflectionClass;
use ReflectionProperty;

abstract class BaseModel {
    protected static array $models = [];

    private function __construct() {}

    public static function create(array $values) {
        $calledClass = static::class;
        $primaryKey = $calledClass::$_primaryKey;
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
        $_record = null;
        if (!!$status) {
          $sql = "SELECT * FROM $table WHERE $primaryKey = LAST_INSERT_ID() LIMIT 1;";
          $stmt = $connection->query($sql);
          $record = $stmt->fetch();

          $_record = new $calledClass;
          $reflection = new ReflectionClass($calledClass);
          foreach ($record as $key => $value) {
            if ($reflection->hasProperty($key)) {
              $property = $reflection->getProperty($key);
              $property->setAccessible(true);
              $property->setValue($_record, $value);
            } else {
              $_record->$key = $value;
            }
          }
        }

        return $_record;
    }

    public function save() {
        $dbInstance = Database::getInstance();
        $connection = $dbInstance->getConnection();

        $primaryKeyName = static::$_primaryKey;
        $primaryKey = $this->$primaryKeyName;

        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties();

        $columns = [];
        $values = [];

        foreach ($properties as $property) {
          $declaringClass = $property->getDeclaringClass()->getName();
          if ($declaringClass !== static::class) continue;
          $property->setAccessible(true);
          $name = $property->getName();
          $value = $property->getValue($this);
          if ($name[0] === '_') continue;

          if ($name === $primaryKeyName) continue;

          $columns[] = "`$name` = ?";
          $values[] = $value;
        }

        $values[] = $primaryKey;
        $table = static::$_table;
        $setClause = implode(', ', $columns);
        $sql = "UPDATE `$table` SET $setClause WHERE `$primaryKeyName` = ?;";

        dump($columns, $values);

        $stmt = $connection->prepare($sql);
        $status = $stmt->execute($values);

        return $status;
    }

    public function destroy() {

    }

    public function find() {

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
        $primaryKey = $calledClass::$_primaryKey;
        $columnSql[] = "$primaryKey INT NOT NULL PRIMARY KEY AUTO_INCREMENT";
        foreach ($calledClass::$_attributes as $name => $options) {
            $column = "$name {$options['type']}";
            if (!empty($options['autoIncrement'])) $column .= " AUTO_INCREMENT";
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
