<?php

session_start();

require_once __DIR__.'/config.php';


function redirect($url) {
    header("Location: $url");
    exit;
}

function require_login() {
    if(!isset($_SESSION['user_id'])) {
        redirect('login.php');
    }
}

function current_user() {
    if(!isset($_SESSION['user_id'])) return null;

    global $pdo;
    $stmt = $pdo->prepare("SELECT id_usuario, nombre_completo, email, telefono, fecha_Nacimiento, passwork_hash, id_rol, id_estatus FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

function is_admin() {
    $user = current_user();
    // Verifica si el usuario existe y si su id_rol es 1 (admin)
    return $user && $user['id_rol'] == 1; 
}





 
