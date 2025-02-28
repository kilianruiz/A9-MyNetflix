<?php
session_start();
require_once '../bbdd/db.php';

// Verificar si es una solicitud POST y si hay datos JSON
$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $data) {
    // Verificar si el usuario tiene permisos de administrador
    if (!isset($_SESSION['id_rol']) || $_SESSION['id_rol'] != 1) {
        echo json_encode([
            'success' => false,
            'message' => 'No tienes permisos para realizar esta acción'
        ]);
        exit;
    }

    try {
        $accion = $data['accion'] ?? '';
        
        // Crear un nuevo rol
        if ($accion === 'crear') {
            $nombre_rol = trim($data['nombre_rol']);
            
            // Verificar si el rol ya existe
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM roles WHERE nombre_rol = ?");
            $stmt->execute([$nombre_rol]);
            if ($stmt->fetchColumn() > 0) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Ya existe un rol con ese nombre'
                ]);
                exit;
            }
            
            // Insertar el nuevo rol
            $stmt = $pdo->prepare("INSERT INTO roles (nombre_rol) VALUES (?)");
            $stmt->execute([$nombre_rol]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Rol creado correctamente'
            ]);
        }
        // Editar un rol existente
        else if ($accion === 'editar') {
            $id_rol = (int)$data['id_rol'];
            $nombre_rol = trim($data['nombre_rol']);
            
            // Verificar si el rol existe
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM roles WHERE id_rol = ?");
            $stmt->execute([$id_rol]);
            if ($stmt->fetchColumn() == 0) {
                echo json_encode([
                    'success' => false,
                    'message' => 'El rol no existe'
                ]);
                exit;
            }
            
            // Verificar si el nuevo nombre ya existe para otro rol
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM roles WHERE nombre_rol = ? AND id_rol != ?");
            $stmt->execute([$nombre_rol, $id_rol]);
            if ($stmt->fetchColumn() > 0) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Ya existe otro rol con ese nombre'
                ]);
                exit;
            }
            
            // Actualizar el rol
            $stmt = $pdo->prepare("UPDATE roles SET nombre_rol = ? WHERE id_rol = ?");
            $stmt->execute([$nombre_rol, $id_rol]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Rol actualizado correctamente'
            ]);
        }
        // Eliminar un rol
        else if ($accion === 'eliminar') {
            $id_rol = (int)$data['id_rol'];
            
            // Verificar si hay usuarios con este rol
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE id_rol = ?");
            $stmt->execute([$id_rol]);
            if ($stmt->fetchColumn() > 0) {
                echo json_encode([
                    'success' => false,
                    'message' => 'No se puede eliminar el rol porque hay usuarios asignados a él'
                ]);
                exit;
            }
            
            // Eliminar el rol
            $stmt = $pdo->prepare("DELETE FROM roles WHERE id_rol = ?");
            $stmt->execute([$id_rol]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Rol eliminado correctamente'
            ]);
        }
        else {
            echo json_encode([
                'success' => false,
                'message' => 'Acción no válida'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error en la base de datos: ' . $e->getMessage()
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Solicitud no válida'
    ]);
}
