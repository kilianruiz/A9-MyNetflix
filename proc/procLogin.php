<?php
session_start();

require_once '../bbdd/db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtén los datos del formulario
    $nombre = $_POST['username'] ?? ''; 
    $password = $_POST['password'] ?? ''; 

    if (empty($nombre) || empty($password)) {
        echo 'Por favor ingresa tu nombre de usuario y contraseña.';
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nombre = :nombre");
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['nombre'];

                // Consultar el rol del usuario
                $stmt_rol = $pdo->prepare("
                    SELECT r.id as rol_id 
                    FROM usuario_rol ur 
                    JOIN roles r ON ur.rol_id = r.id 
                    WHERE ur.usuario_id = :user_id
                ");
                $stmt_rol->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
                $stmt_rol->execute();
                $rol = $stmt_rol->fetch(PDO::FETCH_ASSOC);

                // Redirigir según el rol
                if ($rol['rol_id'] == 1) {
                    header("Location: ../admin/gestionAdmin.php");
                } else {
                    header("Location: ../index.php");
                }
                exit;
            } else {

                echo 'Contraseña incorrecta.';
            }
        } else {

            echo 'Usuario no encontrado.';
        }
    } catch (PDOException $e) {

        echo 'Error de conexión: ' . $e->getMessage();
    }
}
?>
