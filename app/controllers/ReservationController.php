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
        require_once __DIR__ . '/../views/reservations/crear_reservation.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?controller=reservation&action=create');
            return;
        }

        try {
            $data = [
                'user_id' => $_SESSION['user_id'],
                'service_id' => $_POST['service_id'] ?? null,
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

            $result = $this->reservationModel->create($data);

            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
                $this->redirect('index.php?controller=reservation&action=index');
            } else {
                $_SESSION['error'] = $result['message'];
                $this->redirect('index.php?controller=reservation&action=create');
            }
        } catch (Exception $e) {
            Logger::error("Error creando reservación: " . $e->getMessage());
            $_SESSION['error'] = 'Error al crear la reservación';
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
