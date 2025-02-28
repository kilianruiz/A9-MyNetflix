<?php
session_start();
require_once '../bbdd/db.php';

// Verificar si el usuario está autenticado y tiene permisos
if (!isset($_SESSION['id_rol']) || $_SESSION['id_rol'] != 1) {
    echo json_encode([
        'success' => false,
        'message' => 'No tienes permisos para realizar esta acción'
    ]);
    exit;
}

try {
    // Si hay un parámetro de búsqueda, filtrar por él
    if (isset($_GET['query']) && !empty($_GET['query'])) {
        $query = '%' . $_GET['query'] . '%';
        
        $stmt = $pdo->prepare("
            SELECT 
                u.id,
                u.nombre,
                u.email,
                u.fecha_registro,
                r.nombre_rol,
                r.id_rol
            FROM usuarios u
            LEFT JOIN roles r ON u.id_rol = r.id_rol
            WHERE u.nombre LIKE ? OR u.email LIKE ?
            ORDER BY u.fecha_registro DESC
        ");
        $stmt->execute([$query, $query]);
    } else {
        // Si no hay parámetro de búsqueda, devolver todos los usuarios
        $stmt = $pdo->query("
            SELECT 
                u.id,
                u.nombre,
                u.email,
                u.fecha_registro,
                r.nombre_rol,
                r.id_rol
            FROM usuarios u
            LEFT JOIN roles r ON u.id_rol = r.id_rol
            ORDER BY u.fecha_registro DESC
        ");
    }
    
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'usuarios' => $usuarios
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en la base de datos: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>
