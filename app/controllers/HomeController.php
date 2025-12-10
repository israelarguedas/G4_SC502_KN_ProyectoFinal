<?php

class HomeController {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function index() {
        require_once __DIR__ . '/../views/home/index.php';
    }

    public function search() {
        // Obtener parámetros de búsqueda
        $provincia = $_GET['provincia'] ?? '';
        $canton = $_GET['canton'] ?? '';
        $distrito = $_GET['distrito'] ?? '';
        
        // Aquí iría la lógica de búsqueda
        
        require_once __DIR__ . '/../views/home/search_results.php';
    }
}
