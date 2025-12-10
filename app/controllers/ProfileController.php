<?php

class ProfileController {
    private $pdo;
    private $userModel;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
        $this->userModel = new User($this->pdo);
    }

    public function show() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('index.php?action=login');
            return;
        }

        $user = $this->userModel->getUserById($_SESSION['user_id']);
        require_once __DIR__ . '/../views/profile/profile.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?controller=profile&action=show');
            return;
        }

        if (!isset($_SESSION['user_id'])) {
            $this->redirect('index.php?action=login');
            return;
        }

        try {
            $data = [
                'nombre_completo' => filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                'telefono' => filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_FULL_SPECIAL_CHARS)
            ];

            $result = $this->userModel->updateProfile($_SESSION['user_id'], $data);

            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }
        } catch (Exception $e) {
            Logger::error("Error actualizando perfil: " . $e->getMessage());
            $_SESSION['error'] = 'Error al actualizar el perfil';
        }

        $this->redirect('index.php?controller=profile&action=show');
    }

    private function redirect($url) {
        header("Location: $url");
        exit;
    }
}
