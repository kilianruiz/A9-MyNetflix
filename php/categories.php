<?php
include_once '../bbdd/db.php';

try {
    $stmt = $pdo->query("SELECT * FROM categorias ORDER BY nombre");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($categories);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al cargar las categorías']);
} 