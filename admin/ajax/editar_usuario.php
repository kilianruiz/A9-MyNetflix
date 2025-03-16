<?php
session_start();
require_once '../../bbdd/db.php';

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del cuerpo de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);

    $id = (int)$data['id'];
    $nombre = trim($data['nombre']);
    $email = trim($data['email']);
    $rol = (int)$data['rol'];

    try {
        // Validar que el email no pertenezca a otro usuario
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email AND id != :id");
        $stmt->execute([':email' => $email, ':id' => $id]);
        $existeEmail = $stmt->fetchColumn();

        if ($existeEmail > 0) {
            echo json_encode(['success' => false, 'message' => 'El email ya está registrado por otro usuario.']);
            exit;
        }

        // Actualizar el usuario
        $stmt = $pdo->prepare("
            UPDATE usuarios 
            SET nombre = :nombre, email = :email, id_rol = :id_rol
            WHERE id = :id
        ");
        $stmt->execute([
            ':nombre' => $nombre,
            ':email' => $email,
            ':id_rol' => $rol,
            ':id' => $id
        ]);

        echo json_encode(['success' => true, 'message' => 'Usuario actualizado correctamente.']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el usuario: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>