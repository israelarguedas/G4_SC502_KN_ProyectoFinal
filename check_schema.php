<?php
require_once __DIR__ . '/app/config/database.php';

$db = Database::getInstance();
$pdo = $db->getConnection();

try {
    $stmt = $pdo->query("DESCRIBE reservas");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Current reservas table structure:\n\n";
    foreach ($columns as $column) {
        echo "Column: " . $column['Field'] . "\n";
        echo "Type: " . $column['Type'] . "\n";
        echo "Null: " . $column['Null'] . "\n";
        echo "Default: " . $column['Default'] . "\n\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
