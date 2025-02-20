<?php
    session_start();
    header('Content-Type: application/json');

    // Incluir el archivo de conexión a la base de datos
    require_once '../bbdd/db.php';

    try {
        // Verificar que el usuario está autenticado
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
            exit;
        }

        // Obtener los datos enviados desde el frontend
        $data = json_decode(file_get_contents('php://input'), true);

        // Depuración: Mostrar los datos recibidos
        error_log(print_r($data, true));

        // Validar que los datos requeridos están presentes
        if (
            !isset($data['movieId']) || empty($data['movieId']) ||
            !isset($data['action']) || !in_array($data['action'], ['add', 'remove'])
        ) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos o acción inválida']);
            exit;
        }

        // Extraer los datos
        $movieId = intval($data['movieId']); // Convertir a entero para seguridad
        $action = $data['action'];
        $userId = $_SESSION['user_id'];

        // Verificar que la película existe en la base de datos
        $stmt = $pdo->prepare("SELECT id FROM peliculas WHERE id = :movieId");
        $stmt->execute(['movieId' => $movieId]);
        if (!$stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Película no encontrada']);
            exit;
        }

        if ($action === 'add') {
            // Verificar si el usuario ya dio like
            $stmt = $pdo->prepare("SELECT * FROM likes WHERE usuario_id = :userId AND pelicula_id = :movieId");
            $stmt->execute(['userId' => $userId, 'movieId' => $movieId]);
            $existingLike = $stmt->fetch();

            if (!$existingLike) {
                // Insertar el like
                $stmt = $pdo->prepare("INSERT INTO likes (usuario_id, pelicula_id) VALUES (:userId, :movieId)");
                $stmt->execute(['userId' => $userId, 'movieId' => $movieId]);

                // Incrementar el contador de likes en la tabla de películas
                $stmt = $pdo->prepare("UPDATE peliculas SET likes = likes + 1 WHERE id = :movieId");
                $stmt->execute(['movieId' => $movieId]);
            }
        } elseif ($action === 'remove') {
            // Eliminar el like
            $stmt = $pdo->prepare("DELETE FROM likes WHERE usuario_id = :userId AND pelicula_id = :movieId");
            $stmt->execute(['userId' => $userId, 'movieId' => $movieId]);

            // Decrementar el contador de likes en la tabla de películas
            $stmt = $pdo->prepare("UPDATE peliculas SET likes = GREATEST(likes - 1, 0) WHERE id = :movieId");
            $stmt->execute(['movieId' => $movieId]);
        }

        // Obtener el nuevo número de likes
        $stmt = $pdo->prepare("SELECT likes FROM peliculas WHERE id = :movieId");
        $stmt->execute(['movieId' => $movieId]);
        $newLikes = $stmt->fetchColumn();

        // Devolver una respuesta JSON con éxito
        echo json_encode(['success' => true, 'newLikes' => $newLikes]);
    } catch (Exception $e) {
        // Capturar cualquier error y devolver un mensaje de error
        echo json_encode(['success' => false, 'message' => 'Error interno del servidor: ' . $e->getMessage()]);
    }
?>