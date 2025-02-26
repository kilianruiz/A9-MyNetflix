<?php
include_once '../bbdd/db.php';

if (isset($_GET['query'])) {
    $search = '%' . $_GET['query'] . '%';
    
    // Modificamos la consulta para obtener también el conteo de likes
    $stmt = $pdo->prepare("
        SELECT p.*, COUNT(l.id_like_usuario) as likes_count 
        FROM peliculas p 
        LEFT JOIN likes l ON p.id_pelicula = l.pelicula_id 
        WHERE p.title LIKE ? 
        GROUP BY p.id_pelicula
    ");
    
    $stmt->execute([$search]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Formatear los resultados para mantener consistencia con getTopPeliculas.php
    foreach ($results as &$pelicula) {
        $pelicula['likes'] = intval($pelicula['likes_count']);
        unset($pelicula['likes_count']);
    }
    
    echo json_encode($results);
} 