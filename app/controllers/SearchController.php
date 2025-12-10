<?php

class SearchController {
    private $pdo;
    private $locationModel;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
        $this->locationModel = new Location($this->pdo);
    }

    /**
     * Search for businesses/services by location and filters
     */
    public function index() {
        $provincia = $_GET['provincia'] ?? '';
        $canton = $_GET['canton'] ?? '';
        $distrito = $_GET['distrito'] ?? '';
        $categoria = $_GET['categoria'] ?? '';
        $query = $_GET['q'] ?? '';

        $results = $this->searchBusinesses($provincia, $canton, $distrito, $categoria, $query);
        
        require_once __DIR__ . '/../views/search/results.php';
    }

    /**
     * Search businesses with filters
     */
    private function searchBusinesses($provincia, $canton, $distrito, $categoria, $query) {
        try {
            $sql = "
                SELECT DISTINCT
                    n.id_negocio,
                    n.nombre_publico,
                    n.descripcion_corta,
                    n.telefono_contacto,
                    c.nombre_categoria,
                    u.provincia,
                    u.canton,
                    u.distrito,
                    n.ruta_logo,
                    COALESCE(AVG(r.puntuacion), 0) as rating,
                    COUNT(DISTINCT r.id_resena) as num_reviews
                FROM negocios n
                INNER JOIN ubicaciones u ON n.id_ubicacion_fk = u.id_ubicacion
                INNER JOIN categorias c ON n.id_categoria_fk = c.id_categoria
                LEFT JOIN resenas r ON n.id_negocio = r.id_negocio_fk
                WHERE n.id_estatus = 1
            ";

            $params = [];

            if (!empty($provincia)) {
                $sql .= " AND u.provincia = :provincia";
                $params['provincia'] = $provincia;
            }

            if (!empty($canton)) {
                $sql .= " AND u.canton = :canton";
                $params['canton'] = $canton;
            }

            if (!empty($distrito)) {
                $sql .= " AND u.distrito = :distrito";
                $params['distrito'] = $distrito;
            }

            if (!empty($categoria)) {
                $sql .= " AND c.nombre_categoria = :categoria";
                $params['categoria'] = $categoria;
            }

            if (!empty($query)) {
                $sql .= " AND (n.nombre_publico LIKE :query OR n.descripcion_corta LIKE :query)";
                $params['query'] = '%' . $query . '%';
            }

            $sql .= " GROUP BY n.id_negocio ORDER BY rating DESC, num_reviews DESC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error en búsqueda: " . $e->getMessage());
            return [];
        }
    }

    /**
     * API endpoint to get provinces (AJAX)
     */
    public function getProvincias() {
        header('Content-Type: application/json');
        $provincias = $this->locationModel->getProvincias();
        echo json_encode(['success' => true, 'data' => $provincias]);
        exit;
    }

    /**
     * API endpoint to get cantones by provincia (AJAX)
     */
    public function getCantones() {
        header('Content-Type: application/json');
        $provincia = $_GET['provincia'] ?? '';
        
        if (empty($provincia)) {
            echo json_encode(['success' => false, 'message' => 'Provincia requerida']);
            exit;
        }

        $cantones = $this->locationModel->getCantonesByProvincia($provincia);
        echo json_encode(['success' => true, 'data' => $cantones]);
        exit;
    }

    /**
     * API endpoint to get distritos by canton (AJAX)
     */
    public function getDistritos() {
        header('Content-Type: application/json');
        $provincia = $_GET['provincia'] ?? '';
        $canton = $_GET['canton'] ?? '';
        
        if (empty($provincia) || empty($canton)) {
            echo json_encode(['success' => false, 'message' => 'Provincia y cantón requeridos']);
            exit;
        }

        $distritos = $this->locationModel->getDistritosByCanton($provincia, $canton);
        echo json_encode(['success' => true, 'data' => $distritos]);
        exit;
    }
}
