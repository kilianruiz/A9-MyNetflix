<?php
session_start();
require_once "../bbdd/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $id = $_POST['id'];
        $poster_path = null;
        
        // Procesar nueva imagen si se subió una
        if (!empty($_FILES["poster"]["name"])) {
            $target_dir = "../img/";
            $file_extension = strtolower(pathinfo($_FILES["poster"]["name"], PATHINFO_EXTENSION));
            $new_filename = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;
            
            if (move_uploaded_file($_FILES["poster"]["tmp_name"], $target_file)) {
                $poster_path = 'img/' . $new_filename;
            }
        }
        
        // Actualizar la película
        if ($poster_path) {
            $stmt = $pdo->prepare("UPDATE peliculas SET 
                                 title = ?, poster = ?, descripcion = ?, 
                                 autor = ?, fecha_lanzamiento = ?, 
                                 reparto = ?, trailer = ? 
                                 WHERE id_pelicula = ?");
            $stmt->execute([
                $_POST['title'],
                $poster_path,
                $_POST['descripcion'],
                $_POST['autor'],
                $_POST['fecha_lanzamiento'],
                $_POST['reparto'],
                $_POST['trailer'],
                $id
            ]);
        } else {
            $stmt = $pdo->prepare("UPDATE peliculas SET 
                                 title = ?, descripcion = ?, 
                                 autor = ?, fecha_lanzamiento = ?, 
                                 reparto = ?, trailer = ? 
                                 WHERE id_pelicula = ?");
            $stmt->execute([
                $_POST['title'],
                $_POST['descripcion'],
                $_POST['autor'],
                $_POST['fecha_lanzamiento'],
                $_POST['reparto'],
                $_POST['trailer'],
                $id
            ]);
        }
        
        // Actualizar categorías
        $stmt = $pdo->prepare("DELETE FROM pelicula_categoria WHERE id_pelicula = ?");
        $stmt->execute([$id]);
        
        if (isset($_POST['categorias']) && is_array($_POST['categorias'])) {
            $stmt = $pdo->prepare("INSERT INTO pelicula_categoria (id_pelicula, id_categoria) VALUES (?, ?)");
            foreach ($_POST['categorias'] as $categoria_id) {
                $stmt->execute([$id, $categoria_id]);
            }
        }
        
        header("Location: gestionAdmin.php");
        exit();
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} 