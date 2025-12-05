<?php

require_once 'init.php';


if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('login.php');
}

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password_hash']) ? $_POST['password_hash'] : '';
$status = isset($_POST['id_estatus']) ? $_POST['id_estatus'] : '';


if($email === '' || $password === '') {
    redirect('login.php?error='.urlencode('Usuario y contraseña son requeridos'));
}

/*if($status != '1') {
    redirect('login.php?error='.urlencode('Su usuario se encuentra desactivado. Por favor contacte a soporte al correo soporte@ticotrips.com.'));
}*/


try {

    $stmt = $pdo->prepare("SELECT id_usuario, nombre_completo, email, telefono, fecha_Nacimiento, password_hash, id_rol, id_estatus FROM usuarios WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        redirect('login.php?error='.urlencode('Usuario no encontrado'));
    }

    if($password === $user['password_hash']) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id_usuario'];
        $_SESSION['email'] = $user['email'];

        redirect('index.php');
    } else {
        redirect('login-redirect.php?error='.urlencode('Contraseña incorrecta'));
    }
    
} catch (Exception $e) {
    redirect('login.php?error='.urlencode('Error en autenticación'));
}


