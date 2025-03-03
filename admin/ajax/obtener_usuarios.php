<?php
session_start();
require_once '../../bbdd/db.php'; // Ajusta la ruta según tu estructura

try {

    // Obtener usuarios registrados
    $stmt = $pdo->query("
        SELECT 
            u.id,
            u.nombre,
            u.email,
            u.fecha_registro,
            r.nombre_rol,
            r.id_rol
        FROM usuarios u
        LEFT JOIN roles r ON u.id_rol = r.id_rol
        ORDER BY u.fecha_registro DESC
    ");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($usuarios)) {
        echo '<table class="table table-striped">';
        echo '<thead><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Fecha de Registro</th><th>Acciones</th></tr></thead>';
        echo '<tbody>';
        foreach ($usuarios as $usuario) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($usuario['id']) . '</td>';
            echo '<td>' . htmlspecialchars($usuario['nombre']) . '</td>';
            echo '<td>' . htmlspecialchars($usuario['email']) . '</td>';
            echo '<td>' . htmlspecialchars($usuario['nombre_rol']) . '</td>';
            echo '<td>' . htmlspecialchars($usuario['fecha_registro']) . '</td>';
            echo '<td>';
            echo '<button class="btn btn-warning btn-editar-usuario" data-id="' . $usuario['id'] . '">Editar</button> ';
            echo '<button class="btn btn-danger btn-eliminar-usuario ms-2" data-id="' . $usuario['id'] . '">Eliminar</button>';
            echo '</td>';
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