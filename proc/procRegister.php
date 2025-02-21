<?php
session_start();
require_once '../bbdd/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($nombre) || empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
        exit;
    }

    try {
        // Verificar si el email ya existe en usuarios o solicitudes pendientes
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?
                              UNION ALL
                              SELECT COUNT(*) FROM registro_pendiente WHERE email = ?");
        $stmt->execute([$email, $email]);
        $counts = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (array_sum($counts) > 0) {
            echo json_encode(['success' => false, 'message' => 'Este email ya está registrado o tiene una solicitud pendiente']);
            exit;
        }

        // Insertar solicitud de registro
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO registro_pendiente (nombre, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $email, $hashedPassword]);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Solicitud de registro enviada. Por favor, espere la aprobación del administrador.'
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al procesar la solicitud']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
