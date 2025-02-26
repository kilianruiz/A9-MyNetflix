<?php
session_start();
require_once '../bbdd/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validar si los datos existen en $_POST
        if (empty($_POST['nombre']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['rol'])) {
            throw new Exception('Todos los campos son requeridos');
        }

        // Obtener datos del formulario
        $nombre = trim($_POST['nombre']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $rol = intval($_POST['rol']);

        // Validar formato de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('El formato del email no es válido');
        }

        // Verificar si el email ya está registrado
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            throw new Exception('El email ya está registrado');
        }

        // Encriptar la contraseña
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insertar usuario en la base de datos
        $stmt = $pdo->prepare("
            INSERT INTO usuarios (nombre, email, password, id_rol, fecha_registro) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        if ($stmt->execute([$nombre, $email, $hashedPassword, $rol])) {
            echo json_encode(['success' => true, 'message' => 'Usuario creado correctamente']);
        } else {
            throw new Exception('Error al insertar el usuario en la base de datos');
        }

    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>