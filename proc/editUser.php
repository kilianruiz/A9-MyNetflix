<?php
session_start();
require_once '../bbdd/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['id']) || empty($data['nombre']) || empty($data['email']) || empty($data['rol'])) {
            throw new Exception('Faltan campos requeridos');
        }

        // Verificar si el email ya existe para otro usuario
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
        $stmt->execute([$data['email'], $data['id']]);
        if ($stmt->rowCount() > 0) {
            throw new Exception('El email ya está en uso por otro usuario');
        }

        // Preparar la consulta base
        if (!empty($data['password'])) {
            // Si hay nueva contraseña, actualizarla
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("
                UPDATE usuarios 
                SET nombre = ?, email = ?, password = ?, id_rol = ? 
                WHERE id = ?
            ");
            $stmt->execute([
                $data['nombre'],
                $data['email'],
                $hashedPassword,
                $data['rol'],
                $data['id']
            ]);
        } else {
            // Si no hay nueva contraseña, mantener la actual
            $stmt = $pdo->prepare("
                UPDATE usuarios 
                SET nombre = ?, email = ?, id_rol = ? 
                WHERE id = ?
            ");
            $stmt->execute([
                $data['nombre'],
                $data['email'],
                $data['rol'],
                $data['id']
            ]);
        }

        echo json_encode(['success' => true, 'message' => 'Usuario actualizado correctamente']);

    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?> 