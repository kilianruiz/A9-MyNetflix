<?php
session_start();
require_once '../bbdd/db.php';

try {
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Consulta para obtener las 5 películas con más likes
    $topMoviesQuery = "
        SELECT 
            p.*,
            COUNT(DISTINCT l.id_like_usuario) as likes,
            IF(:user_id IS NOT NULL, COUNT(DISTINCT CASE WHEN l.usuario_id = :user_id THEN l.id_like_usuario END), 0) as user_liked
        FROM peliculas p
        LEFT JOIN likes l ON p.id_pelicula = l.pelicula_id
        GROUP BY p.id_pelicula
        ORDER BY likes DESC
        LIMIT 5
    ";

    $stmt = $pdo->prepare($topMoviesQuery);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $topMovies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Consulta para obtener el resto de las películas
    $otherMoviesQuery = "
        SELECT 
            p.*,
            COUNT(DISTINCT l.id_like_usuario) as likes,
            IF(:user_id IS NOT NULL, COUNT(DISTINCT CASE WHEN l.usuario_id = :user_id THEN l.id_like_usuario END), 0) as user_liked
        FROM peliculas p
        LEFT JOIN likes l ON p.id_pelicula = l.pelicula_id
        GROUP BY p.id_pelicula
        ORDER BY likes DESC
    ";

    $stmt = $pdo->prepare($otherMoviesQuery);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    // Excluir las películas ya incluidas en $topMovies
    $topMovieIds = array_column($topMovies, 'id_pelicula');
    $otherMovies = array_filter($stmt->fetchAll(PDO::FETCH_ASSOC), function ($movie) use ($topMovieIds) {
        return !in_array($movie['id_pelicula'], $topMovieIds);
    });

    // Convertir los valores numéricos correctamente
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

    // Devolver ambas listas como JSON
    header('Content-Type: application/json');
    echo json_encode([
        'topMovies' => $topMovies,
        'otherMovies' => array_values($otherMovies) // Reindexar el array
    ]);

} catch (PDOException $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}
?>