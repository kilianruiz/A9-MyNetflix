<?php
session_start();

require_once '../bbdd/db.php'; 

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($nombre) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Por favor ingresa tu nombre de usuario y contraseña.']);
        exit;
    }

    try {
        // Buscar por nombre o email
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nombre = :nombre OR email = :email");
        $stmt->execute([
            ':nombre' => $nombre,
            ':email' => $nombre
        ]);
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            // Guardar datos importantes en la sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['nombre'];
            $_SESSION['rol'] = $user['id_rol'];
            $_SESSION['email'] = $user['email'];
            
            if ($user['id_rol'] == 1) {
                header('Location: ../admin/gestionAdmin.php');
            } else {
                header('Location: ../index.php');
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Usuario o contraseña incorrectos']);
        }
    } catch (PDOException $e) {
        error_log("Error en login: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error de conexión']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
