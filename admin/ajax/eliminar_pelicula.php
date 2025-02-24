<?php
session_start();
require_once "../../bbdd/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    try {
        $id = $_POST['id'];
        
        // Iniciar transacción
        $pdo->beginTransaction();
        
        // Eliminar relaciones en la tabla pelicula_categoria
        $stmt = $pdo->prepare("DELETE FROM pelicula_categoria WHERE id_pelicula = ?");
        $stmt->execute([$id]);
        
        // Eliminar likes asociados
        $stmt = $pdo->prepare("DELETE FROM likes WHERE pelicula_id = ?");
        $stmt->execute([$id]);
        
        // Eliminar la película
        $stmt = $pdo->prepare("DELETE FROM peliculas WHERE id_pelicula = ?");
        $stmt->execute([$id]);
        
        // Confirmar transacción
        $pdo->commit();
        
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        // Revertir transacción en caso de error
        $pdo->rollBack();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
} 