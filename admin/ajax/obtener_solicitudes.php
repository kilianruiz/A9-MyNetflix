<?php
session_start();
require_once '../../bbdd/db.php';

try {
    // Obtener solicitudes pendientes
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

    if (!empty($solicitudes)) {
        echo '<table class="table">';
        echo '<thead><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Fecha de Solicitud</th><th>Acciones</th></tr></thead>';
        echo '<tbody>';
        foreach ($solicitudes as $solicitud) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($solicitud['id_solicitud']) . '</td>';
            echo '<td>' . htmlspecialchars($solicitud['nombre']) . '</td>';
            echo '<td>' . htmlspecialchars($solicitud['email']) . '</td>';
            echo '<td>' . htmlspecialchars($solicitud['fecha_registro']) . '</td>';
            echo '<td>';
            echo '<button class="btn-aceptar" data-id="' . htmlspecialchars($solicitud['id_solicitud']) . '">Aceptar</button>';
            echo ' ';
            echo '<button class="btn-rechazar" data-id="' . htmlspecialchars($solicitud['id_solicitud']) . '">Rechazar</button>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<p>No hay solicitudes pendientes.</p>';
    }
} catch (PDOException $e) {
    echo '<p>Error al obtener las solicitudes: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>