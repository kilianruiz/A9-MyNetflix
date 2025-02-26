<?php
session_start();
require_once '../bbdd/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['id'])) {
            throw new Exception('ID de usuario no proporcionado');
        }

        // Verificar que no sea el último administrador
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as admin_count 
            FROM usuarios 
            WHERE id_rol = 1 AND id != ?
        ");
        $stmt->execute([$data['id']]);
        $adminCount = $stmt->fetch(PDO::FETCH_ASSOC)['admin_count'];

        $stmt = $pdo->prepare("SELECT id_rol FROM usuarios WHERE id = ?");
        $stmt->execute([$data['id']]);
        $userRole = $stmt->fetch(PDO::FETCH_ASSOC)['id_rol'];

        if ($userRole == 1 && $adminCount == 0) {
            throw new Exception('No se puede eliminar el último administrador');
        }

        // Eliminar usuario
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$data['id']]);

        echo json_encode(['success' => true, 'message' => 'Usuario eliminado correctamente']);

    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?> 