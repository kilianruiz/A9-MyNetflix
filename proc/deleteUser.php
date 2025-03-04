<?php
session_start();
require_once '../bbdd/db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: No se proporcionó un ID de usuario válido.");
}

$id_usuario = (int) $_GET['id'];

try {
    // Verificar que el usuario existe
    $stmt = $pdo->prepare("SELECT id_rol FROM usuarios WHERE id = ?");
    $stmt->execute([$id_usuario]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        die("Error: Usuario no encontrado.");
    }

    // Verificar que no sea el último administrador
    if ($usuario['id_rol'] == 1) {
        $stmt = $pdo->query("SELECT COUNT(*) as admin_count FROM usuarios WHERE id_rol = 1");
        $adminCount = $stmt->fetch(PDO::FETCH_ASSOC)['admin_count'];

        if ($adminCount <= 1) {
            die("Error: No se puede eliminar el último administrador.");
        }
    }

    // Eliminar usuario
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$id_usuario]);

    if ($stmt->rowCount() > 0) {
        echo "<script>
                alert('Usuario eliminado correctamente.');
                window.location.href = '../admin/gestion_solicitudes.php'; // Redirigir a la lista de usuarios
              </script>";
    } else {
        echo "Error: No se pudo eliminar el usuario.";
    }

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
