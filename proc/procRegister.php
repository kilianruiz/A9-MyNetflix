<?php
session_start();
require_once '../bbdd/db.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($nombre) || empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'El formato del email no es válido']);
        exit;
    }

    try {
        // Verificar si el email o nombre ya existe en usuarios o solicitudes pendientes
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ? OR nombre = ?");
        $stmt->execute([$email, $nombre]);
        $existsUser = $stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM registro_pendiente WHERE email = ? OR nombre = ?");
        $stmt->execute([$email, $nombre]);
        $existsPending = $stmt->fetchColumn();
        
        if ($existsUser > 0 || $existsPending > 0) {
            echo json_encode(['success' => false, 'message' => 'El email o nombre de usuario ya está registrado o pendiente de aprobación']);
            exit;
        }

        // Insertar solicitud de registro
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO registro_pendiente (nombre, email, password, fecha_registro) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$nombre, $email, $hashedPassword]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Solicitud de registro enviada. Por favor, espera la aprobación del administrador.',
            'pending' => true
        ]);

    } catch (PDOException $e) {
        error_log("Error en registro: " . $e->getMessage());
        echo json_encode([
            'success' => false, 
            'message' => 'Error al procesar el registro. Por favor, inténtalo de nuevo.'
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>