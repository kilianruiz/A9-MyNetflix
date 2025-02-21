<?php
    session_start();

    require_once '../bbdd/db.php';

    // Verificar si el usuario está autenticado
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para dar like.']);
        exit;
    }

    try {

        $data = json_decode(file_get_contents('php://input'), true);
        $movieId = intval($data['movieId']);
        $action = $data['action'];

        // ID del usuario actual
        $userId = $_SESSION['user_id'];

        if ($action === 'add') {
            // Agregar like
            $stmt = $pdo->prepare("INSERT INTO likes (usuario_id, pelicula_id) VALUES (?, ?)");
            $stmt->execute([$userId, $movieId]);
        } elseif ($action === 'remove') {
            // Quitar like
            $stmt = $pdo->prepare("DELETE FROM likes WHERE usuario_id = ? AND pelicula_id = ?");
            $stmt->execute([$userId, $movieId]);
        }

        // Actualizar el contador de likes en la tabla `peliculas`
        $stmt = $pdo->prepare("UPDATE peliculas SET likes = (SELECT COUNT(*) FROM likes WHERE pelicula_id = ?) WHERE id = ?");
        $stmt->execute([$movieId, $movieId]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
?>