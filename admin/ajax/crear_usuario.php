<?php
session_start();
require_once '../../bbdd/db.php';

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del cuerpo de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);

    $nombre = trim($data['nombre']);
    $email = trim($data['email']);
    $rol = (int)$data['rol'];

    try {
        // Validar que el email no exista previamente
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $existeEmail = $stmt->fetchColumn();

        if ($existeEmail > 0) {
            echo json_encode(['success' => false, 'message' => 'El email ya está registrado.']);
            exit;
        }

        // Insertar el nuevo usuario
        $password = password_hash('default_password', PASSWORD_BCRYPT); // Contraseña por defecto
        $stmt = $pdo->prepare("
            INSERT INTO usuarios (nombre, email, password, id_rol, fecha_registro)
            VALUES (:nombre, :email, :password, :id_rol, NOW())
        ");
        $stmt->execute([
            ':nombre' => $nombre,
            ':email' => $email,
            ':password' => $password,
            ':id_rol' => $rol
        ]);

        echo json_encode(['success' => true, 'message' => 'Usuario creado correctamente.']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al crear el usuario: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>