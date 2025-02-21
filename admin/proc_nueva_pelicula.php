<?php
session_start();
require_once "../bbdd/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Procesar la imagen
        $target_dir = "../img/";
        $file_extension = strtolower(pathinfo($_FILES["poster"]["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        // Verificar y mover el archivo
        if (move_uploaded_file($_FILES["poster"]["tmp_name"], $target_file)) {
            $poster_path = 'img/' . $new_filename;
            
            // Insertar la película
            $stmt = $pdo->prepare("INSERT INTO peliculas (title, poster, descripcion, autor, fecha_lanzamiento, reparto, trailer) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            $stmt->execute([
                $_POST['title'],
                $poster_path,
                $_POST['descripcion'],
                $_POST['autor'],
                $_POST['fecha_lanzamiento'],
                $_POST['reparto'],
                $_POST['trailer']
            ]);
            
            $pelicula_id = $pdo->lastInsertId();
            
            // Insertar categorías
            if (isset($_POST['categorias']) && is_array($_POST['categorias'])) {
                $stmt = $pdo->prepare("INSERT INTO pelicula_categoria (id_pelicula, id_categoria) VALUES (?, ?)");
                foreach ($_POST['categorias'] as $categoria_id) {
                    $stmt->execute([$pelicula_id, $categoria_id]);
                }
            }
            
            header("Location: gestionAdmin.php");
            exit();
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} 