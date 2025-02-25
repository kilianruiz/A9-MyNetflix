<?php
require_once '../bbdd/db.php';

// Obtener solicitudes pendientes
try {
    $stmt = $pdo->query("
        SELECT 
            rp.id_solicitud,
            rp.nombre,
            rp.email,
            rp.fecha_registro,
            rp.estado
        FROM registro_pendiente rp
        WHERE rp.estado = 'pendiente'
        ORDER BY rp.fecha_registro DESC
    ");
    $solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error al obtener las solicitudes: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Solicitudes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body class="bg-dark text-white">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">
                <img src="../img/logo-grande.png" alt="Logo" height="40">
            </a>
            <div class="d-flex">
                <a href="./gestionAdmin.php" class="btn btn-outline-light me-2">Volver</a>
                <a href="../proc/logout.php" class="btn btn-danger">Cerrar sesión</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="mb-4">Gestión de Solicitudes de Registro</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (empty($solicitudes)): ?>
            <div class="alert alert-info">No hay solicitudes pendientes.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Fecha de Solicitud</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($solicitudes as $solicitud): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($solicitud['id_solicitud']); ?></td>
                            <td><?php echo htmlspecialchars($solicitud['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($solicitud['email']); ?></td>
                            <td><?php echo htmlspecialchars($solicitud['fecha_registro']); ?></td>
                            <td>
                                <button class="btn btn-success btn-sm" 
                                        onclick="gestionarSolicitud(<?php echo $solicitud['id_solicitud']; ?>, 'aceptar')">
                                    Aceptar
                                </button>
                                <button class="btn btn-danger btn-sm"
                                        onclick="gestionarSolicitud(<?php echo $solicitud['id_solicitud']; ?>, 'rechazar')">
                                    Rechazar
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <script>
    function gestionarSolicitud(id, accion) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: `¿Deseas ${accion} esta solicitud?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, confirmar',
            cancelButtonText: 'Cancelar',
            background: '#212529',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
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
                        Swal.fire({
                            title: '¡Completado!',
                            text: data.message,
                            icon: 'success',
                            background: '#212529',
                            color: '#fff'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: data.message || 'Error al procesar la solicitud',
                            icon: 'error',
                            background: '#212529',
                            color: '#fff'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Error al procesar la solicitud',
                        icon: 'error',
                        background: '#212529',
                        color: '#fff'
                    });
                });
            }
        });
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 