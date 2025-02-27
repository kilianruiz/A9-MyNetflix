<?php
session_start();
include_once '../bbdd/db.php';

try {
    // Obtener los datos POST como JSON
    $data = json_decode(file_get_contents('php://input'), true);
    $searchQuery = isset($_GET['query']) ? $_GET['query'] : '';
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    
    // Construir la consulta base
    $query = "SELECT p.*, 
              COUNT(DISTINCT l.id_like_usuario) as likes,
              IF(:user_id IS NOT NULL, COUNT(DISTINCT CASE WHEN l.usuario_id = :user_id THEN l.id_like_usuario END), 0) as user_liked
              FROM peliculas p
              LEFT JOIN likes l ON p.id_pelicula = l.pelicula_id
              WHERE 1=1";
    
    $params = [':user_id' => $userId];

    // Aplicar filtro de búsqueda si existe
    if (!empty($searchQuery)) {
        $query .= " AND p.title LIKE :search";
        $params[':search'] = "%$searchQuery%";
    }

    // Aplicar filtros de likes si están presentes y el usuario está logueado
    if ($userId) {
        if (!empty($data['liked'])) {
            $query .= " AND EXISTS (SELECT 1 FROM likes WHERE pelicula_id = p.id_pelicula AND usuario_id = :user_id_liked)";
            $params[':user_id_liked'] = $userId;
        }
        if (!empty($data['notLiked'])) {
            $query .= " AND NOT EXISTS (SELECT 1 FROM likes WHERE pelicula_id = p.id_pelicula AND usuario_id = :user_id_not_liked)";
            $params[':user_id_not_liked'] = $userId;
        }
    }

    // Agrupar y ordenar resultados
    $query .= " GROUP BY p.id_pelicula ORDER BY likes DESC";

    $stmt = $pdo->prepare($query);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Formatear los resultados
    foreach ($results as &$movie) {
        $movie['likes'] = intval($movie['likes']);
        $movie['user_liked'] = boolval($movie['user_liked']);
    }
    
    header('Content-Type: application/json');
    echo json_encode($results);

} catch (PDOException $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}
?> 