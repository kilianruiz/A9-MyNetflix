<?php
session_start();
require_once '../bbdd/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['id']) || empty($data['estado'])) {
            throw new Exception('Datos incompletos');
        }

        // Obtener datos de la solicitud
        $stmt = $pdo->prepare("SELECT * FROM registro_pendiente WHERE id_solicitud = ?");
        $stmt->execute([$data['id']]);
        $solicitud = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$solicitud) {
            throw new Exception('Solicitud no encontrada');
        }

        if ($data['estado'] === 'aprobada') {
            // Verificar que el email no exista ya en usuarios
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
            $stmt->execute([$solicitud['email']]);
            if ($stmt->rowCount() > 0) {
                throw new Exception('El email ya está registrado en el sistema');
            }

            // Insertar nuevo usuario
            $stmt = $pdo->prepare("
                INSERT INTO usuarios (nombre, email, password, id_rol, fecha_registro) 
                VALUES (?, ?, ?, 2, NOW())
            ");
            $stmt->execute([
                $solicitud['nombre'],
                $solicitud['email'],
                $solicitud['password']
            ]);
        }

        // Eliminar solicitud pendiente
        $stmt = $pdo->prepare("DELETE FROM registro_pendiente WHERE id_solicitud = ?");
        $stmt->execute([$data['id']]);

        echo json_encode(['success' => true, 'message' => 'Solicitud procesada correctamente']);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>