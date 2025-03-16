<?php
session_start();
require_once '../../bbdd/db.php';

try {
    $stmt = $pdo->query("SELECT id_rol, nombre_rol FROM roles ORDER BY id_rol");
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los roles como JSON
    header('Content-Type: application/json');
    echo json_encode($roles);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener los roles: ' . $e->getMessage()]);
}
?>