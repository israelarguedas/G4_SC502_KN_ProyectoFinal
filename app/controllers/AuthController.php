<?php

class AuthController {
    private $pdo;
    private $authModel;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
        $this->authModel = new Auth($this->pdo);
    }

    public function showLogin() {
        $error = isset($_GET['error']) ? $_GET['error'] : '';
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?action=login');
            return;
        }

        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password_hash']) ? $_POST['password_hash'] : '';

        if ($email === '' || $password === '') {
            $this->redirect('index.php?action=login&error=' . urlencode('Usuario y contraseña son requeridos'));
            return;
        }

        try {
            $user = $this->authModel->getUserByEmail($email);

            if (!$user) {
                $this->redirect('index.php?action=login&error=' . urlencode('Usuario no encontrado'));
                return;
            }

            if (password_verify($password, $user['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id_usuario'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['id_rol'] = $user['id_rol'];

                $this->redirect('index.php');
            } else {
                $this->redirect('index.php?action=login&error=' . urlencode('Contraseña incorrecta'));
            }
        } catch (Exception $e) {
            Logger::error("Error en login: " . $e->getMessage());
            $this->redirect('index.php?action=login&error=' . urlencode('Error en autenticación'));
        }
    }

    public function showRegister() {
        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?action=register');
            return;
        }

        $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
        $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $fecha_nacimiento = filter_input(INPUT_POST, 'fecha_nacimiento', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = $_POST['password'] ?? '';
        $id_rol = filter_input(INPUT_POST, 'rol', FILTER_VALIDATE_INT);

        // Validaciones
        if (empty($nombre) || empty($correo) || empty($password) || empty($id_rol)) {
            $_SESSION['error'] = 'Por favor, complete todos los campos requeridos.';
            $this->redirect('index.php?action=register');
            return;
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'El formato del correo electrónico no es válido.';
            $this->redirect('index.php?action=register');
            return;
        }

        try {
            $result = $this->authModel->createUser([
                'nombre' => $nombre,
                'email' => $correo,
                'telefono' => $telefono,
                'fecha_nacimiento' => $fecha_nacimiento ?: null,
                'password' => $password,
                'id_rol' => $id_rol
            ]);

            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
                $this->redirect('index.php?action=login');
            } else {
                $_SESSION['error'] = $result['message'];
                $this->redirect('index.php?action=register');
            }
        } catch (Exception $e) {
            Logger::error("Error en registro: " . $e->getMessage());
            $_SESSION['error'] = 'Error en el registro. Intente más tarde.';
            $this->redirect('index.php?action=register');
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('index.php');
    }

    private function redirect($url) {
        header("Location: $url");
        exit;
    }

    public function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('index.php?action=login');
        }
    }

    public function isAdmin() {
        return isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 1;
    }
}
