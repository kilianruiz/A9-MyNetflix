<?php
session_start();
require_once "../bbdd/db.php";

// Obtener categorías para el formulario
$stmt = $pdo->query("SELECT * FROM categorias");
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Película</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/stylesAdmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <img src="../img/logo-grande.png" alt="" class="navbar-logo">
            <a href="gestionAdmin.php" class="btn-volver">Volver</a>
        </div>
    </nav>

    <div class="container">
        <div class="editar-pelicula-container">
            <form action="proc_nueva_pelicula.php" method="POST" enctype="multipart/form-data" id="peliculaForm" onsubmit="return validarFormulario()">
                <div id="mensajesError" class="alert alert-danger" style="display: none; margin-top: 20px; background-color: #2f0000; border-color: #e50914; color: white;"></div>
                <br>
                <br>
                <div class="mb-3">
                    <label for="title" class="form-label">Título</label>
                    <input type="text" class="form-control" id="title" name="title">
                </div>
                
                <div class="mb-3">
                    <label for="poster" class="form-label">Poster</label>
                    <input type="file" class="form-control" id="poster" name="poster" accept="image/*">
                </div>
                
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="autor" class="form-label">Autor</label>
                    <input type="text" class="form-control" id="autor" name="autor">
                </div>
                
                <div class="mb-3">
                    <label for="fecha_lanzamiento" class="form-label">Fecha de Lanzamiento</label>
                    <input type="date" class="form-control" id="fecha_lanzamiento" name="fecha_lanzamiento">
                </div>
                
                <div class="mb-3">
                    <label for="reparto" class="form-label">Reparto</label>
                    <input type="text" class="form-control" id="reparto" name="reparto">
                </div>
                
                <div class="mb-3">
                    <label for="trailer" class="form-label">URL del Trailer</label>
                    <input type="url" class="form-control" id="trailer" name="trailer">
                </div>
    
                <div class="mb-3">
                    <label class="form-label">Categorías</label>
                    <?php foreach ($categorias as $categoria): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="categorias[]" 
                                   value="<?php echo $categoria['id_categoria']; ?>" 
                                   id="categoria<?php echo $categoria['id_categoria']; ?>">
                            <label class="form-check-label" for="categoria<?php echo $categoria['id_categoria']; ?>">
                                <?php echo $categoria['nombre_categoria']; ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <button type="submit" class="btn-nuevo">Guardar Película</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/validaciones.js"></script>
</body>
</html> 