<?php
    header('Content-Type: application/json'); 

    require_once '../bbdd/db.php'; 

    try {
        // Consulta para obtener todas las películas con sus detalles
        $stmt = $pdo->query("SELECT p.*, COUNT(l.id_like_usuario) as likes 
                            FROM peliculas p 
                            LEFT JOIN likes l ON p.id_pelicula = l.pelicula_id 
                            GROUP BY p.id_pelicula 
                            ORDER BY likes DESC");
        $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($movies);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
?>
