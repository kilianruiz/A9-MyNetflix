<?php
session_start();
require_once '../bbdd/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['nombre']) || empty($data['email']) || empty($data['password']) || empty($data['rol'])) {
            throw new Exception('Faltan campos requeridos');
        }

        // Verificar si el email ya existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$data['email']]);
        if ($stmt->rowCount() > 0) {
            throw new Exception('El email ya está en uso');
        }

        // Insertar nuevo usuario
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("
            INSERT INTO usuarios (nombre, email, password, id_rol, fecha_registro) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->execute([
            $data['nombre'],
            $data['email'],
            $hashedPassword,
            $data['rol']
        ]);

        echo json_encode(['success' => true, 'message' => 'Usuario creado correctamente']);

    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?> 