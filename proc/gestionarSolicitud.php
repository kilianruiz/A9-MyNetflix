<?php
session_start();
require_once '../bbdd/db.php';

header('Content-Type: application/json');

// Verificar si el usuario es admin
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 1) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

// Recibir y validar datos
$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? null;
$accion = $data['accion'] ?? null;

if (!$id || !$accion) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Obtener datos de la solicitud
    $stmt = $pdo->prepare("SELECT * FROM registro_pendiente WHERE id_solicitud = ?");
    $stmt->execute([$id]);
    $solicitud = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$solicitud) {
        throw new Exception('Solicitud no encontrada');
    }

    if ($accion === 'aceptar') {
        // Verificar si el usuario ya existe
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ? OR nombre = ?");
        $stmt->execute([$solicitud['email'], $solicitud['nombre']]);
        if ($stmt->fetchColumn() > 0) {
            throw new Exception('El usuario ya existe en el sistema');
        }

        // Insertar nuevo usuario con rol 2 (usuario normal)
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, id_rol) VALUES (?, ?, ?, 2)");
        $success = $stmt->execute([
            $solicitud['nombre'],
            $solicitud['email'],
            $solicitud['password'] // La contraseña ya está hasheada desde el registro
        ]);

        if (!$success) {
            throw new Exception('Error al crear el usuario');
        }
    }

    // Eliminar la solicitud
    $stmt = $pdo->prepare("DELETE FROM registro_pendiente WHERE id_solicitud = ?");
    if (!$stmt->execute([$id])) {
        throw new Exception('Error al eliminar la solicitud');
    }

    $pdo->commit();
    
    echo json_encode([
        'success' => true,
        'message' => $accion === 'aceptar' ? 'Usuario registrado correctamente' : 'Solicitud rechazada'
    ]);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage()
    ]);
}
?> 