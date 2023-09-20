<?php

namespace Pine\App;

use PDO;
use PDOException;

class Database {

    private $connection = null;
    private $driver = "mysql";

    public function __construct(
        private string $host,
        private string $username,
        private string $password,
        private string $name,
        private int $port,
        private string $charset,
    ) {
        $dsn = "{$this->driver}:host={$this->host};port={$this->port};dbname={$this->name};charset={$this->charset}";

        try {
            $this->connection = new PDO($dsn, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->connection;
    }

    public static function connect() {
        $db = new Database(
            $_ENV["DB_HOST"],
            $_ENV["DB_USERNAME"],
            $_ENV["DB_PASSWORD"],
            $_ENV["DB_NAME"],
            $_ENV["DB_PORT"],
            $_ENV["DB_CHARSET"]);

        return $db->getConnection();
    }
}
