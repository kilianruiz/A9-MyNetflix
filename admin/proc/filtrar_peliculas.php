<?php
require_once "../../bbdd/db.php";

$titulo = $_POST['titulo'] ?? '';
$autor = $_POST['autor'] ?? '';
$fecha = $_POST['fecha'] ?? '';
$ordenLikes = $_POST['ordenLikes'] ?? 'none';
$categoria = $_POST['categoria'] ?? '';

// Query base con JOIN para likes y categorías
$query = "SELECT p.*, 
          COUNT(DISTINCT l.id_like_usuario) as total_likes,
          GROUP_CONCAT(DISTINCT c.nombre_categoria) as categorias
          FROM peliculas p 
          LEFT JOIN likes l ON p.id_pelicula = l.pelicula_id 
          LEFT JOIN pelicula_categoria pc ON p.id_pelicula = pc.id_pelicula
          LEFT JOIN categorias c ON pc.id_categoria = c.id_categoria
          WHERE 1=1";

if (!empty($titulo)) {
    $query .= " AND p.title LIKE :titulo";
}

if (!empty($autor)) {
    $query .= " AND p.autor LIKE :autor";
}

if (!empty($fecha)) {
    $query .= " AND DATE(p.fecha_lanzamiento) = :fecha";
}

if (!empty($categoria)) {
    $query .= " AND c.id_categoria = :categoria";
}

$query .= " GROUP BY p.id_pelicula";

if ($ordenLikes !== 'none') {
    $query .= " ORDER BY total_likes " . ($ordenLikes === 'asc' ? 'ASC' : 'DESC');
}

$stmt = $pdo->prepare($query);

if (!empty($titulo)) {
    $stmt->bindValue(':titulo', "%$titulo%");
}
if (!empty($autor)) {
    $stmt->bindValue(':autor', "%$autor%");
}
if (!empty($fecha)) {
    $stmt->bindValue(':fecha', $fecha);
}
if (!empty($categoria)) {
    $stmt->bindValue(':categoria', $categoria);
}

$stmt->execute();
$peliculas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Incluir el template de la tabla
include '../../templates/tabla_peliculas.php'; 