<?php
header('Content-Type: application/json'); 

include '../bbdd/db.php'; 

$query = 'SELECT * FROM peliculas';
$stmt = $pdo->query($query);

$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($movies);
?>
