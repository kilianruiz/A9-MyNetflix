<?php
session_start();
require_once '../bbdd/db.php';

// Verificar si el usuario es admin
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'Admin') {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

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
    $stmt = $pdo->prepare("SELECT * FROM registro_pendiente WHERE id = ?");
    $stmt->execute([$id]);
    $solicitud = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($accion === 'aceptar') {
        // Insertar nuevo usuario
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, id_rol) VALUES (?, ?, ?, 2)");
        $stmt->execute([
            $solicitud['nombre'],
            $solicitud['email'],
            $solicitud['password']
        ]);

        // Actualizar estado de la solicitud
        $stmt = $pdo->prepare("UPDATE registro_pendiente SET estado = 'aceptado' WHERE id = ?");
        $stmt->execute([$id]);
    } else {
        // Rechazar solicitud
        $stmt = $pdo->prepare("UPDATE registro_pendiente SET estado = 'rechazado' WHERE id = ?");
        $stmt->execute([$id]);
    }

    $pdo->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?> 