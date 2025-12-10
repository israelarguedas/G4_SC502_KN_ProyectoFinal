<?php

class ReservationController {
    private $pdo;
    private $reservationModel;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
        $this->reservationModel = new Reservation($this->pdo);
    }

    public function index() {
        require_once __DIR__ . '/../views/reservations/listar_reservations.php';
    }

    public function create() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Debe iniciar sesión para hacer una reservación.';
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        // Get service_id from URL
        $serviceId = isset($_GET['service_id']) ? intval($_GET['service_id']) : 0;
        
        if ($serviceId <= 0) {
            $_SESSION['error'] = 'Debe seleccionar un servicio para reservar.';
            header('Location: index.php?controller=home&action=search');
            exit;
        }
        
        // Fetch service details
        $businessModel = new Business($this->pdo);
        $service = $this->getServiceById($serviceId);
        
        if (!$service) {
            $_SESSION['error'] = 'Servicio no encontrado.';
            header('Location: index.php?controller=home&action=search');
            exit;
        }
        
        require_once __DIR__ . '/../views/reservations/crear_reservation.php';
    }

    private function getServiceById($serviceId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT s.*, n.nombre_publico as nombre_negocio, c.nombre_categoria
                FROM servicios s
                JOIN negocios n ON s.id_negocio_fk = n.id_negocio
                LEFT JOIN categorias c ON s.id_categoria_fk = c.id_categoria
                WHERE s.id_servicio = ? AND s.id_estatus = 1
            ");
            $stmt->execute([$serviceId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Error obteniendo servicio: " . $e->getMessage());
            return null;
        }
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?controller=reservation&action=create');
            return;
        }

        try {
            $data = [
                'user_id' => $_SESSION['user_id'],
                'service_id' => $_POST['id_servicio'] ?? null,
                'fecha' => $_POST['fecha'] ?? null,
                'hora' => $_POST['hora'] ?? null,
                'personas' => $_POST['personas'] ?? 1,
                'nombre' => $_POST['nombre'] ?? '',
                'email' => $_POST['email'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'tipo_identificacion' => $_POST['tipo_identificacion'] ?? '',
                'numero_identificacion' => $_POST['numero_identificacion'] ?? '',
                'pais' => $_POST['pais'] ?? ''
            ];

            Logger::info("Attempting to create reservation with data: " . json_encode($data));
            
            // Validate required fields
            if (empty($data['service_id']) || $data['service_id'] <= 0) {
                $_SESSION['error'] = 'Debe seleccionar un servicio válido.';
                $this->redirect('index.php?controller=home&action=search');
                return;
            }
            
            $result = $this->reservationModel->create($data);
            Logger::info("Reservation result: " . json_encode($result));

            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
                $this->redirect('index.php?controller=reservation&action=myReservations');
            } else {
                $_SESSION['error'] = $result['message'];
                $this->redirect('index.php?controller=reservation&action=create');
            }
        } catch (Exception $e) {
            Logger::error("Error creando reservación: " . $e->getMessage());
            $_SESSION['error'] = 'Error en la base de datos';
            $this->redirect('index.php?controller=reservation&action=create');
        }
    }

    public function myReservations() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('index.php?action=login');
            return;
        }

        $reservations = $this->reservationModel->getByUserId($_SESSION['user_id']);
        require_once __DIR__ . '/../views/reservations/mis_reservations.php';
    }

    private function redirect($url) {
        header("Location: $url");
        exit;
    }
}
