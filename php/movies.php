<?php
session_start();
require_once '../bbdd/db.php';

try {
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    
    $query = "
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

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    
    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Convertir los valores numéricos correctamente
    foreach ($movies as &$movie) {
        $movie['id'] = intval($movie['id_pelicula']);
        $movie['likes'] = intval($movie['likes']);
        $movie['user_liked'] = intval($movie['user_liked']) > 0;
    }

    header('Content-Type: application/json');
    echo json_encode($movies);

} catch (PDOException $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}
?>
