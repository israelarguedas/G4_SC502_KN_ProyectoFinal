<?php

class HomeController {
    private $pdo;
    private $businessModel;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
        $this->businessModel = new Business($this->pdo);
    }

    public function index() {
        // Obtener negocios destacados (aprobados)
        $featuredBusinesses = $this->businessModel->getFeaturedBusinesses(10);
        require_once __DIR__ . '/../views/home/index.php';
    }

    public function search() {
        // Obtener parámetros de búsqueda
        $provincia = $_GET['provincia'] ?? '';
        $canton = $_GET['canton'] ?? '';
        $distrito = $_GET['distrito'] ?? '';
        $categoria = $_GET['categoria'] ?? '';
        
        // Buscar negocios usando el modelo
        $results = $this->businessModel->searchBusinesses([
            'provincia' => $provincia,
            'canton' => $canton,
            'distrito' => $distrito,
            'categoria' => $categoria
        ]);
        
        require_once __DIR__ . '/../views/home/search_results.php';
    }
}
