<?php
session_start();

require_once '../bbdd/db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtén los datos del formulario
    $username = $_POST['username'] ?? ''; 
    $password = $_POST['password'] ?? ''; 

    if (empty($username) || empty($password)) {
        echo 'Por favor ingresa tu nombre de usuario y contraseña.';
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                echo '¡Login correcto!';
                header('Location: index.html?bien');
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
