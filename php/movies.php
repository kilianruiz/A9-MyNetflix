<?php
session_start();
require_once '../bbdd/db.php';

try {
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    
    // Obtener los filtros del body
    $data = json_decode(file_get_contents('php://input'), true);
    $filters = $data ?? [];

    // Base de la consulta
    $baseQuery = "
        SELECT 
            p.*,
            COUNT(DISTINCT l.id_like_usuario) as likes,
            IF(:user_id IS NOT NULL, COUNT(DISTINCT CASE WHEN l.usuario_id = :user_id THEN l.id_like_usuario END), 0) as user_liked
        FROM peliculas p
        LEFT JOIN likes l ON p.id_pelicula = l.pelicula_id
    ";

    // Agregar condiciones según los filtros
    $whereConditions = [];
    if (!empty($filters['liked']) && $userId) {
        $whereConditions[] = "EXISTS (SELECT 1 FROM likes WHERE pelicula_id = p.id_pelicula AND usuario_id = :user_id)";
    }
    if (!empty($filters['notLiked']) && $userId) {
        $whereConditions[] = "NOT EXISTS (SELECT 1 FROM likes WHERE pelicula_id = p.id_pelicula AND usuario_id = :user_id)";
    }

    if (!empty($whereConditions)) {
        $baseQuery .= " WHERE " . implode(" AND ", $whereConditions);
    }

    $baseQuery .= " GROUP BY p.id_pelicula";

    // Consulta para las películas top
    $topMoviesQuery = $baseQuery . " ORDER BY likes DESC LIMIT 5";
    
    // Consulta para el resto de películas
    $otherMoviesQuery = $baseQuery . " ORDER BY fecha_lanzamiento DESC";

    // Ejecutar consultas
    $stmt = $pdo->prepare($topMoviesQuery);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $topMovies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare($otherMoviesQuery);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    
    // Filtrar las películas que ya están en top
    $topMovieIds = array_column($topMovies, 'id_pelicula');
    $otherMovies = array_filter($stmt->fetchAll(PDO::FETCH_ASSOC), function ($movie) use ($topMovieIds) {
        return !in_array($movie['id_pelicula'], $topMovieIds);
    });

    // Formatear los datos
    foreach ($topMovies as &$movie) {
        $movie['id'] = intval($movie['id_pelicula']);
        $movie['likes'] = intval($movie['likes']);
        $movie['user_liked'] = boolval($movie['user_liked']);
    }

    foreach ($otherMovies as &$movie) {
        $movie['id'] = intval($movie['id_pelicula']);
        $movie['likes'] = intval($movie['likes']);
        $movie['user_liked'] = boolval($movie['user_liked']);
    }

    // Devolver resultados
    header('Content-Type: application/json');
    echo json_encode([
        'topMovies' => $topMovies,
        'otherMovies' => array_values($otherMovies)
    ]);

} catch (PDOException $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}
?>