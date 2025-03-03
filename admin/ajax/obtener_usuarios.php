<?php
session_start();
require_once '../../bbdd/db.php';

try {
    // Obtener usuarios registrados
    $stmt = $pdo->query("
        SELECT 
            u.id,
            u.nombre,
            u.email,
            u.fecha_registro,
            r.nombre_rol
        FROM usuarios u
        LEFT JOIN roles r ON u.id_rol = r.id_rol
        ORDER BY u.fecha_registro DESC
    ");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($usuarios)) {
        echo '<table class="table">';
        echo '<thead><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Fecha de Registro</th></tr></thead>';
        echo '<tbody>';
        foreach ($usuarios as $usuario) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($usuario['id']) . '</td>';
            echo '<td>' . htmlspecialchars($usuario['nombre']) . '</td>';
            echo '<td>' . htmlspecialchars($usuario['email']) . '</td>';
            echo '<td>' . htmlspecialchars($usuario['nombre_rol']) . '</td>';
            echo '<td>' . htmlspecialchars($usuario['fecha_registro']) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<p>No hay usuarios registrados.</p>';
    }
} catch (PDOException $e) {
    echo '<p>Error al obtener los usuarios: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>