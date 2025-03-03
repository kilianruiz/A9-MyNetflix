<?php
session_start();
require_once '../bbdd/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['id'])) {
            throw new Exception('ID de solicitud no proporcionado');
        }

        // Eliminar solicitud
        $stmt = $pdo->prepare("DELETE FROM registro_pendiente WHERE id = ?");
        $stmt->execute([$data['id']]);

        echo json_encode(['success' => true, 'message' => 'Solicitud eliminada correctamente']);

    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?> 