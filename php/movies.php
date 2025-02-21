<?php
session_start();
header('Content-Type: application/json'); 

require_once '../bbdd/db.php'; 

try {
    $userId = $_SESSION['user_id'] ?? null;

    // Consulta para obtener todas las películas con sus detalles
    $stmt = $pdo->query("SELECT p.*, 
                                COUNT(l.id_like_usuario) as likes, 
                                IF(l.usuario_id IS NOT NULL, 1, 0) as user_liked 
                         FROM peliculas p 
                         LEFT JOIN likes l ON p.id_pelicula = l.pelicula_id 
                         " . ($userId ? "LEFT JOIN likes ul ON p.id_pelicula = ul.pelicula_id AND ul.usuario_id = $userId" : "") . "
                         GROUP BY p.id_pelicula 
                         ORDER BY likes DESC");
    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Depuración: Verificar los datos obtenidos
    if (!$movies) {
        echo json_encode(['error' => 'No se encontraron películas']);
    } else {
        echo json_encode($movies);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
