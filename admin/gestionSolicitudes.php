<?php
session_start();
require_once '../bbdd/db.php';

// Verificar si el usuario es admin
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'Admin') {
    header('Location: ../index.php');
    exit;
}

// Obtener solicitudes pendientes
$stmt = $pdo->query("SELECT * FROM registro_pendiente WHERE estado = 'pendiente'");
$solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Solicitudes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Solicitudes de Registro Pendientes</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Fecha de Solicitud</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($solicitudes as $solicitud): ?>
                <tr>
                    <td><?php echo htmlspecialchars($solicitud['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($solicitud['email']); ?></td>
                    <td><?php echo htmlspecialchars($solicitud['fecha_solicitud']); ?></td>
                    <td>
                        <button class="btn btn-success btn-sm" 
                                onclick="gestionarSolicitud(<?php echo $solicitud['id']; ?>, 'aceptar')">
                            Aceptar
                        </button>
                        <button class="btn btn-danger btn-sm"
                                onclick="gestionarSolicitud(<?php echo $solicitud['id']; ?>, 'rechazar')">
                            Rechazar
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
    function gestionarSolicitud(id, accion) {
        fetch('../proc/gestionarSolicitud.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: id,
                accion: accion
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error al procesar la solicitud');
            }
        })
        .catch(error => console.error('Error:', error));
    }
    </script>
</body>
</html> 