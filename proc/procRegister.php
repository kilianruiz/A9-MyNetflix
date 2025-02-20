<?php
session_start();
require_once '../bbdd/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Validar que todos los campos estén llenos
    if (empty($nombre) || empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
        exit;
    }

    try {
        // Verificar si el email ya existe
        $stmt = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $emailExists = $stmt->fetchColumn();

        if ($emailExists > 0) {
            echo json_encode(['success' => false, 'message' => 'Este email ya está registrado']);
            exit;
        }

        // Si el email no existe, proceder con el registro
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        // Insertar nuevo usuario
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $email, $hashedPassword]);
        
        // Asignar rol de Suscriptor por defecto
        $userId = $conn->lastInsertId();
        $stmt = $conn->prepare("INSERT INTO usuario_rol (usuario_id, rol_id) VALUES (?, 2)"); // 2 es el ID del rol Suscriptor
        $stmt->execute([$userId]);

        echo json_encode(['success' => true, 'message' => 'Usuario registrado correctamente']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al registrar el usuario']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
