<?php

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
        $this->connection->set_charset("utf8mb4");
    }

    public static function getConnection()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->connection;
    }
}

// We still define the constants here (or you can keep them in config.php)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'Succ00$$');
define('DB_NAME', 'codemastery');