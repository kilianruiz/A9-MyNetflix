<?php
    session_start();

    require_once '../bbdd/db.php';

    header('Content-Type: application/json');

    // Verificar si el usuario está autenticado
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para dar like.']);
        exit;
    }

    try {
        // Verificar que recibimos los datos correctamente
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if (!isset($data['movieId']) || !isset($data['action'])) {
            throw new Exception('Datos incompletos');
        }

        $movieId = intval($data['movieId']);
        $action = $data['action'];
        $userId = $_SESSION['user_id'];

        // Verificar que la película existe
        $checkMovie = $pdo->prepare("SELECT id_pelicula FROM peliculas WHERE id_pelicula = ?");
        $checkMovie->execute([$movieId]);
        if (!$checkMovie->fetch()) {
            throw new Exception('Película no encontrada');
        }

        if ($action === 'add') {
            // Intentar agregar el like
            $stmt = $pdo->prepare("INSERT INTO likes (usuario_id, pelicula_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE usuario_id = usuario_id");
            $stmt->execute([$userId, $movieId]);
        } elseif ($action === 'remove') {
            // Quitar like
            $stmt = $pdo->prepare("DELETE FROM likes WHERE usuario_id = ? AND pelicula_id = ?");
            $stmt->execute([$userId, $movieId]);
        }

        // Obtener el nuevo conteo de likes
        $stmt = $pdo->prepare("SELECT COUNT(*) as total_likes FROM likes WHERE pelicula_id = ?");
        $stmt->execute([$movieId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verificar si el usuario actual tiene like en esta película
        $stmt = $pdo->prepare("SELECT COUNT(*) as user_liked FROM likes WHERE usuario_id = ? AND pelicula_id = ?");
        $stmt->execute([$userId, $movieId]);
        $userLiked = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'likes' => $result['total_likes'],
            'userLiked' => $userLiked['user_liked'] > 0,
            'debug' => [
                'userId' => $userId,
                'movieId' => $movieId,
                'action' => $action
            ]
        ]);

    } catch (Exception $e) {
        echo json_encode([
            'success' => false, 
            'message' => $e->getMessage(),
            'debug' => [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]
        ]);
    }
?>