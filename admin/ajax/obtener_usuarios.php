<?php
session_start();
require_once '../../bbdd/db.php'; 

try {
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
        echo '<table>';
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
            echo '<a href="#" class="btn btn-sm btn-warning" onclick="editarUsuario(' . $usuario['id'] . ', \'' . addslashes($usuario['nombre']) . '\', \'' . addslashes($usuario['email']) . '\', ' . $usuario['id_rol'] . ')">
                    <i class="fas fa-edit"></i> Editar
                </a>';
                  echo '<a href="../proc/deleteUser.php?id=' . urlencode($usuario['id']) . '" 
                          class="btn btn-sm btn-danger"
                          onclick="return confirm(\'¿Estás seguro de que quieres eliminar este usuario?\');">
                          <i class="fas fa-trash"></i> Eliminar
                        </a>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<p>No hay usuarios registrados.</p>';
    }
} catch (PDOException $e) {
    echo '<p>Error al obtener los datos: ' . $e->getMessage() . '</p>';
}
?>
