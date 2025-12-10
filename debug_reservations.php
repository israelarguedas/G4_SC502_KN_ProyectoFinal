<?php
require_once __DIR__ . '/app/config/database.php';

$db = Database::getInstance();
$pdo = $db->getConnection();

echo "=== DEBUG RESERVATIONS ===\n\n";

// Check reservas table
$stmt = $pdo->query("SELECT * FROM reservas");
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Total reservas: " . count($reservas) . "\n\n";

foreach ($reservas as $r) {
    echo "ID: " . $r['id_reserva'] . "\n";
    echo "Usuario: " . $r['id_usuario_fk'] . "\n";
    echo "Servicio: " . $r['id_servicio_fk'] . "\n";
    echo "Fecha: " . $r['fecha_reserva'] . "\n";
    echo "Personas: " . $r['cantidad_personas'] . "\n";
    echo "Total: " . $r['total_pagar'] . "\n";
    echo "Status: " . $r['id_estatus'] . "\n";
    echo "---\n";
}

// Now test the full query with joins
echo "\n=== TESTING FULL JOIN QUERY ===\n\n";

try {
    $stmt = $pdo->prepare("
        SELECT r.*, s.titulo as nombre_servicio, n.nombre_publico as nombre_negocio,
               e.nombre as nombre_estatus, c.nombre_categoria,
               u.provincia, u.canton, u.distrito
        FROM reservas r
        JOIN servicios s ON r.id_servicio_fk = s.id_servicio
        JOIN negocios n ON s.id_negocio_fk = n.id_negocio
        JOIN estatus e ON r.id_estatus = e.id_estatus
        JOIN categorias c ON s.id_categoria_fk = c.id_categoria
        JOIN ubicaciones u ON n.id_ubicacion_fk = u.id_ubicacion
        WHERE r.id_usuario_fk = 4
        ORDER BY r.fecha_reserva DESC, r.fecha_creacion DESC
    ");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Results with joins: " . count($results) . "\n\n";
    
    foreach ($results as $r) {
        echo "Servicio: " . $r['nombre_servicio'] . "\n";
        echo "Negocio: " . $r['nombre_negocio'] . "\n";
        echo "Fecha: " . $r['fecha_reserva'] . "\n";
        echo "---\n";
    }
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
