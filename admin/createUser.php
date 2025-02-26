<?php
session_start();
require_once '../bbdd/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validar datos requeridos
        if (empty($data['nombre']) || empty($data['email']) || empty($data['password']) || empty($data['rol'])) {
            throw new Exception('Todos los campos son requeridos');
        }

        // Verificar si el email ya existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$data['email']]);
        if ($stmt->rowCount() > 0) {
            throw new Exception('El email ya está registrado');
        }

        // Hash de la contraseña
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        // Insertar usuario
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