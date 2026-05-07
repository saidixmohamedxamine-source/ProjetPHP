<?php
/**
 * Database Connection
 */

require_once __DIR__ . '/../includes/config.php';

class Database {
    private $connection;

    public function __construct() {
        try {
            // Debug: Check if constants are defined
            if (!defined('DB_HOST') || !defined('DB_USER') || !defined('DB_NAME')) {
                throw new Exception('Database configuration constants not defined');
            }

            $this->connection = new mysqli(
                DB_HOST,
                DB_USER,
                DB_PASSWORD,
                DB_NAME
            );

            if ($this->connection->connect_error) {
                throw new Exception('Database connection failed: ' . $this->connection->connect_error);
            }

            $this->connection->set_charset('utf8mb4');
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            exit;
        }
    }

    public function getConnection() {
        return $this->connection;
    }

    public function closeConnection() {
        $this->connection->close();
    }

    public function query($sql) {
        return $this->connection->query($sql);
    }

    public function prepare($sql) {
        return $this->connection->prepare($sql);
    }
}

// Create global database instance
$db = new Database();
