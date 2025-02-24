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

                // Verificar el rol directamente de la tabla usuarios
                if ($user['id_rol'] == 1) {
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
