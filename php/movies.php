<?php
    header('Content-Type: application/json'); 

    require_once '../bbdd/db.php'; 

    try {

        // Consulta para obtener todas las películas con sus detalles
        $stmt = $pdo->query("SELECT * FROM peliculas ORDER BY likes DESC");
        $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($movies);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
?>