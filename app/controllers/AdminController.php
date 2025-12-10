<?php

class AdminController {
    private $pdo;
    private $adminModel;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
        $this->adminModel = new Admin($this->pdo);
        
        // Verificar que el usuario es administrador
        if (!$this->isAdmin()) {
            $_SESSION['error'] = 'Acceso denegado. Debe ser Administrador.';
            header("Location: index.php");
            exit;
        }
    }

    public function index() {
        // Obtener datos para el dashboard
        $pendingBusinesses = $this->adminModel->getPendingBusinesses();
        $reservationStats = $this->adminModel->getReservationStats();
        $statistics = $this->adminModel->getGeneralStats();
        
        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    public function businesses() {
        $pendingBusinesses = $this->adminModel->getPendingBusinesses();
        $approvedBusinesses = $this->adminModel->getApprovedBusinesses();
        
        require_once __DIR__ . '/../views/admin/businesses.php';
    }

    public function approveBusiness() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?controller=admin&action=businesses');
            return;
        }

        $businessId = $_POST['business_id'] ?? null;
        
        if (!$businessId) {
            $_SESSION['error'] = 'ID de negocio no válido';
            $this->redirect('index.php?controller=admin&action=businesses');
            return;
        }

        try {
            $result = $this->adminModel->approveBusiness($businessId);
            
            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }
        } catch (Exception $e) {
            Logger::error("Error aprobando negocio: " . $e->getMessage());
            $_SESSION['error'] = 'Error al aprobar el negocio';
        }

        // Redirigir al dashboard para ver los cambios
        $this->redirect('index.php?controller=admin&action=index'); 
    }

    public function rejectBusiness() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?controller=admin&action=businesses');
            return;
        }

        $businessId = $_POST['business_id'] ?? null;
        $reason = $_POST['reason'] ?? '';
        
        if (!$businessId) {
            $_SESSION['error'] = 'ID de negocio no válido';
            $this->redirect('index.php?controller=admin&action=businesses');
            return;
        }

        try {
            $result = $this->adminModel->rejectBusiness($businessId, $reason);
            
            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }
        } catch (Exception $e) {
            Logger::error("Error rechazando negocio: " . $e->getMessage());
            $_SESSION['error'] = 'Error al rechazar el negocio';
        }

        // Redirigir al dashboard para ver los cambios
        $this->redirect('index.php?controller=admin&action=index');
    }

    public function reservations() {
        $stats = $this->adminModel->getReservationStats();
        $recentReservations = $this->adminModel->getRecentReservations();
        
        require_once __DIR__ . '/../views/admin/reservations.php';
    }

    public function statistics() {
        $stats = $this->adminModel->getGeneralStats();
        $topSearches = $this->adminModel->getTopSearches();
        $popularBusinesses = $this->adminModel->getPopularBusinesses();
        
        require_once __DIR__ . '/../views/admin/statistics.php';
    }

    public function updateBusinessStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        $businessId = $_POST['business_id'] ?? null;
        $status = $_POST['status'] ?? null;

        try {
            $result = $this->adminModel->updateBusinessStatus($businessId, $status);
            echo json_encode($result);
        } catch (Exception $e) {
            Logger::error("Error actualizando estado: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error al actualizar estado']);
        }
    }

    private function isAdmin() {
        return isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 1;
    }

    private function redirect($url) {
        header("Location: $url");
        exit;
    }
}