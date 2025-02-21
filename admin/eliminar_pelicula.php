<?php
session_start();
require_once "../bbdd/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    try {
        $id = $_POST['id'];
        
        // Primero eliminar las relaciones en la tabla pelicula_categoria
        $stmt = $pdo->prepare("DELETE FROM pelicula_categoria WHERE id_pelicula = ?");
        $stmt->execute([$id]);
        
        // Luego eliminar los likes asociados
        $stmt = $pdo->prepare("DELETE FROM likes WHERE pelicula_id = ?");
        $stmt->execute([$id]);
        
        // Finalmente eliminar la película
        $stmt = $pdo->prepare("DELETE FROM peliculas WHERE id_pelicula = ?");
        $stmt->execute([$id]);
        
        header("Location: gestionAdmin.php");
        exit();
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} 