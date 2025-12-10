<?php

class Database {
    private static $instance = null;
    private $pdo;

    private $db_host = 'localhost';
    private $db_name = 'tico_trips_db';
    private $db_user = 'root';
    private $db_pass = 'admin';
    private $db_charset = 'utf8mb4';
    private $db_port = 3306;

    private function __construct() {
        $dsn = "mysql:host={$this->db_host};dbname={$this->db_name};charset={$this->db_charset};port={$this->db_port}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];

        try {
            $this->pdo = new PDO($dsn, $this->db_user, $this->db_pass, $options);
        } catch (PDOException $e) {
            error_log("Error de conexión a la base de datos: " . $e->getMessage());
            die("Error de conexión a la base de datos. Contacte al administrador.");
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }

    // Prevenir clonación
    private function __clone() {}

    // Prevenir deserialización
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}
