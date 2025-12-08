<?php

session_start();

require_once __DIR__.'/config.php';
require_once __DIR__.'/functions.php';


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
    $stmt = $pdo->prepare("SELECT id_usuario, nombre_completo, email, telefono, fecha_Nacimiento, password_hash, id_rol, id_estatus FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

function is_admin() {
    $user = current_user();
    return $user && $user['id_rol'] == 1; 
}

function is_comercio() {
    $user = current_user();
    return $user && $user['id_rol'] == 3; 
}







 
