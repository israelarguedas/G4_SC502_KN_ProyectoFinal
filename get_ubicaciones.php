<?php
require_once 'init.php';
require_once 'functions.php';

global $pdo; 

// Establecer cabeceras para indicar que la respuesta es JSON
header('Content-Type: application/json');

$provincia = filter_input(INPUT_GET, 'provincia', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$canton = filter_input(INPUT_GET, 'canton', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

// Lógica para devolver Cantones o Distritos
if ($provincia && !$canton) {
    // Consulta optimizada para Cantones
    $stmt = $pdo->prepare("SELECT DISTINCT canton FROM ubicaciones WHERE provincia = ? ORDER BY canton");
    $stmt->execute([$provincia]);
    $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo json_encode($results);
} elseif ($provincia && $canton) {
    // 2. Petición para obtener Distritos (dado Provincia Y Cantón)
    $stmt = $pdo->prepare("SELECT DISTINCT distrito FROM ubicaciones WHERE provincia = ? AND canton = ? ORDER BY distrito");
    $stmt->execute([$provincia, $canton]);
    $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo json_encode($results);
} else {
    // Error si la petición es incorrecta
    echo json_encode(['error' => 'Parámetros inválidos']);
}

exit;
?>