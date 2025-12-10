<?php

class BusinessController {
    private $pdo;
    private $businessModel;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
        $this->businessModel = new Business($this->pdo);
    }

    public function showApplication() {
        // Verificar que el usuario sea comercio
        if (!$this->isComercio()) {
            $_SESSION['error'] = 'Acceso denegado. Es necesario registrar un Usuario como Comercio/Negocio.';
            $this->redirect('index.php?action=register');
            return;
        }

        $provincias = $this->businessModel->getProvincias();
        require_once __DIR__ . '/../views/business/application.php';
    }

    public function submitApplication() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?controller=business&action=showApplication');
            return;
        }

        if (!$this->isComercio()) {
            $_SESSION['error'] = 'Acceso denegado.';
            $this->redirect('index.php');
            return;
        }

        try {
            $data = [
                'id_usuario_fk' => $_SESSION['user_id'],
                'nombre_legal' => filter_input(INPUT_POST, 'nombre_legal', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'nombre_publico' => filter_input(INPUT_POST, 'nombre_publico', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'tipo_negocio' => filter_input(INPUT_POST, 'tipo_negocio', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'descripcion_corta' => filter_input(INPUT_POST, 'descripcion_negocio', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'telefono_contacto' => filter_input(INPUT_POST, 'telefono_negocio', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'correo_contacto' => filter_input(INPUT_POST, 'correo_negocio', FILTER_SANITIZE_EMAIL),
                'tipo_cedula' => filter_input(INPUT_POST, 'tipo_cedula', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'cedula_hacienda' => filter_input(INPUT_POST, 'cedula_hacienda', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'nombre_representante' => filter_input(INPUT_POST, 'nombre_representante_hacienda', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'no_licencia_municipal' => filter_input(INPUT_POST, 'registro_municipal', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'provincia' => filter_input(INPUT_POST, 'provincia', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'canton' => filter_input(INPUT_POST, 'canton', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'distrito' => filter_input(INPUT_POST, 'distrito', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'direccion_exacta' => filter_input(INPUT_POST, 'direccion_exacta', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'link_google_maps' => filter_input(INPUT_POST, 'google_maps_link', FILTER_SANITIZE_URL),
                'link_waze' => filter_input(INPUT_POST, 'waze_link', FILTER_SANITIZE_URL)
            ];

            $result = $this->businessModel->createApplication($data);

            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
                $this->redirect('index.php');
            } else {
                $_SESSION['error'] = $result['message'];
                $this->redirect('index.php?controller=business&action=showApplication');
            }
        } catch (Exception $e) {
            Logger::error("Error en aplicación de negocio: " . $e->getMessage());
            $_SESSION['error'] = 'Error al procesar la aplicación';
            $this->redirect('index.php?controller=business&action=showApplication');
        }
    }

    public function showPromotions() {
        $cupones = $this->businessModel->getActiveCoupons();
        $es_negocio = $this->isComercio();
        $negocio_id = null;

        if ($es_negocio && isset($_SESSION['user_id'])) {
            $negocio_id = $this->businessModel->getBusinessIdByUserId($_SESSION['user_id']);
        }

        require_once __DIR__ . '/../views/business/promotions.php';
    }

    public function manageCoupons() {
        if (!$this->isComercio()) {
            $this->redirect('index.php');
            return;
        }

        $negocio_id = $this->businessModel->getBusinessIdByUserId($_SESSION['user_id']);
        
        if (!$negocio_id) {
            $_SESSION['error'] = 'Negocio no encontrado.';
            $this->redirect('index.php');
            return;
        }

        $cupones = $this->businessModel->getCouponsByBusinessId($negocio_id);
        require_once __DIR__ . '/../views/business/manage_coupons.php';
    }

    public function view() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($id <= 0) {
            $_SESSION['error'] = 'Negocio no encontrado.';
            $this->redirect('index.php');
            return;
        }

        $business = $this->businessModel->getById($id);
        
        if (!$business) {
            $_SESSION['error'] = 'Negocio no encontrado.';
            $this->redirect('index.php');
            return;
        }

        $services = $this->businessModel->getServicesByBusinessId($id);
        require_once __DIR__ . '/../views/business/view.php';
    }

    public function dashboard() {
        if (!$this->isComercio()) {
            $this->redirect('index.php');
            return;
        }

        $negocio_id = $this->businessModel->getBusinessIdByUserId($_SESSION['user_id']);
        
        if (!$negocio_id) {
            $_SESSION['error'] = 'Negocio no encontrado.';
            $this->redirect('index.php');
            return;
        }

        $business = $this->businessModel->getById($negocio_id);
        $stats = $this->businessModel->getBusinessStats($negocio_id);
        $servicios = $this->businessModel->getAllServices($negocio_id);
        $cupones = $this->businessModel->getCouponsByBusinessId($negocio_id);
        $reservas_recientes = $this->businessModel->getReservationsByBusinessId($negocio_id, 5);

        require_once __DIR__ . '/../views/business/dashboard.php';
    }

    public function manageServices() {
        if (!$this->isComercio()) {
            $this->redirect('index.php');
            return;
        }

        $negocio_id = $this->businessModel->getBusinessIdByUserId($_SESSION['user_id']);
        
        if (!$negocio_id) {
            $_SESSION['error'] = 'Negocio no encontrado.';
            $this->redirect('index.php');
            return;
        }

        $servicios = $this->businessModel->getAllServices($negocio_id);
        $categorias = $this->businessModel->getCategories();

        require_once __DIR__ . '/../views/business/manage_services.php';
    }

    public function createService() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?controller=business&action=manageServices');
            return;
        }

        if (!$this->isComercio()) {
            $_SESSION['error'] = 'Acceso denegado.';
            $this->redirect('index.php');
            return;
        }

        $negocio_id = $this->businessModel->getBusinessIdByUserId($_SESSION['user_id']);
        
        if (!$negocio_id) {
            $_SESSION['error'] = 'Negocio no encontrado.';
            $this->redirect('index.php');
            return;
        }

        try {
            $data = [
                'id_negocio_fk' => $negocio_id,
                'nombre_servicio' => filter_input(INPUT_POST, 'nombre_servicio', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'descripcion' => filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'precio_base' => filter_input(INPUT_POST, 'precio_base', FILTER_VALIDATE_FLOAT),
                'id_categoria_fk' => filter_input(INPUT_POST, 'id_categoria_fk', FILTER_VALIDATE_INT)
            ];

            $result = $this->businessModel->createService($data);

            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }
        } catch (Exception $e) {
            Logger::error("Error creando servicio: " . $e->getMessage());
            $_SESSION['error'] = 'Error al crear servicio';
        }

        $this->redirect('index.php?controller=business&action=manageServices');
    }

    public function updateService() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?controller=business&action=manageServices');
            return;
        }

        if (!$this->isComercio()) {
            $_SESSION['error'] = 'Acceso denegado.';
            $this->redirect('index.php');
            return;
        }

        $negocio_id = $this->businessModel->getBusinessIdByUserId($_SESSION['user_id']);
        $service_id = filter_input(INPUT_POST, 'id_servicio', FILTER_VALIDATE_INT);

        if (!$negocio_id || !$service_id) {
            $_SESSION['error'] = 'Datos inválidos.';
            $this->redirect('index.php?controller=business&action=manageServices');
            return;
        }

        try {
            $data = [
                'id_negocio_fk' => $negocio_id,
                'nombre_servicio' => filter_input(INPUT_POST, 'nombre_servicio', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'descripcion' => filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'precio_base' => filter_input(INPUT_POST, 'precio_base', FILTER_VALIDATE_FLOAT),
                'id_categoria_fk' => filter_input(INPUT_POST, 'id_categoria_fk', FILTER_VALIDATE_INT)
            ];

            $result = $this->businessModel->updateService($service_id, $data);

            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }
        } catch (Exception $e) {
            Logger::error("Error actualizando servicio: " . $e->getMessage());
            $_SESSION['error'] = 'Error al actualizar servicio';
        }

        $this->redirect('index.php?controller=business&action=manageServices');
    }

    public function deleteService() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?controller=business&action=manageServices');
            return;
        }

        if (!$this->isComercio()) {
            $_SESSION['error'] = 'Acceso denegado.';
            $this->redirect('index.php');
            return;
        }

        $negocio_id = $this->businessModel->getBusinessIdByUserId($_SESSION['user_id']);
        $service_id = filter_input(INPUT_POST, 'id_servicio', FILTER_VALIDATE_INT);

        if (!$negocio_id || !$service_id) {
            $_SESSION['error'] = 'Datos inválidos.';
            $this->redirect('index.php?controller=business&action=manageServices');
            return;
        }

        try {
            $result = $this->businessModel->deleteService($service_id);

            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }
        } catch (Exception $e) {
            Logger::error("Error eliminando servicio: " . $e->getMessage());
            $_SESSION['error'] = 'Error al eliminar servicio';
        }

        $this->redirect('index.php?controller=business&action=manageServices');
    }

    public function createCoupon() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?controller=business&action=manageCoupons');
            return;
        }

        if (!$this->isComercio()) {
            $_SESSION['error'] = 'Acceso denegado.';
            $this->redirect('index.php');
            return;
        }

        $negocio_id = $this->businessModel->getBusinessIdByUserId($_SESSION['user_id']);
        
        if (!$negocio_id) {
            $_SESSION['error'] = 'Negocio no encontrado.';
            $this->redirect('index.php');
            return;
        }

        try {
            $data = [
                'id_negocio_fk' => $negocio_id,
                'codigo_cupon' => filter_input(INPUT_POST, 'codigo_cupon', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'tipo_descuento' => filter_input(INPUT_POST, 'tipo_descuento', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'valor_descuento' => filter_input(INPUT_POST, 'valor_descuento', FILTER_VALIDATE_FLOAT),
                'fecha_inicio' => filter_input(INPUT_POST, 'fecha_inicio', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'fecha_fin' => filter_input(INPUT_POST, 'fecha_fin', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'usos_restantes' => filter_input(INPUT_POST, 'usos_restantes', FILTER_VALIDATE_INT)
            ];

            $result = $this->businessModel->createCoupon($data);

            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }
        } catch (Exception $e) {
            Logger::error("Error creando cupón: " . $e->getMessage());
            $_SESSION['error'] = 'Error al crear cupón';
        }

        $this->redirect('index.php?controller=business&action=manageCoupons');
    }

    public function updateCoupon() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?controller=business&action=manageCoupons');
            return;
        }

        if (!$this->isComercio()) {
            $_SESSION['error'] = 'Acceso denegado.';
            $this->redirect('index.php');
            return;
        }

        $coupon_id = filter_input(INPUT_POST, 'id_cupon', FILTER_VALIDATE_INT);

        if (!$coupon_id) {
            $_SESSION['error'] = 'Datos inválidos.';
            $this->redirect('index.php?controller=business&action=manageCoupons');
            return;
        }

        try {
            $data = [
                'codigo_cupon' => filter_input(INPUT_POST, 'codigo_cupon', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'tipo_descuento' => filter_input(INPUT_POST, 'tipo_descuento', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'valor_descuento' => filter_input(INPUT_POST, 'valor_descuento', FILTER_VALIDATE_FLOAT),
                'fecha_inicio' => filter_input(INPUT_POST, 'fecha_inicio', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'fecha_fin' => filter_input(INPUT_POST, 'fecha_fin', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'usos_restantes' => filter_input(INPUT_POST, 'usos_restantes', FILTER_VALIDATE_INT),
                'id_estatus' => filter_input(INPUT_POST, 'id_estatus', FILTER_VALIDATE_INT)
            ];

            $result = $this->businessModel->updateCoupon($coupon_id, $data);

            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }
        } catch (Exception $e) {
            Logger::error("Error actualizando cupón: " . $e->getMessage());
            $_SESSION['error'] = 'Error al actualizar cupón';
        }

        $this->redirect('index.php?controller=business&action=manageCoupons');
    }

    public function deleteCoupon() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?controller=business&action=manageCoupons');
            return;
        }

        if (!$this->isComercio()) {
            $_SESSION['error'] = 'Acceso denegado.';
            $this->redirect('index.php');
            return;
        }

        $coupon_id = filter_input(INPUT_POST, 'id_cupon', FILTER_VALIDATE_INT);

        if (!$coupon_id) {
            $_SESSION['error'] = 'Datos inválidos.';
            $this->redirect('index.php?controller=business&action=manageCoupons');
            return;
        }

        try {
            $result = $this->businessModel->deleteCoupon($coupon_id);

            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }
        } catch (Exception $e) {
            Logger::error("Error eliminando cupón: " . $e->getMessage());
            $_SESSION['error'] = 'Error al eliminar cupón';
        }

        $this->redirect('index.php?controller=business&action=manageCoupons');
    }

    private function isComercio() {
        return isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], [3, 4, 5, 6]);
    }

    private function redirect($url) {
        header("Location: $url");
        exit;
    }
}
