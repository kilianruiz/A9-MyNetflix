<?php
session_start();
require_once "../bbdd/db.php";

if (!isset($_GET['id'])) {
    header("Location: gestionAdmin.php");
    exit();
}

$id = $_GET['id'];

// Obtener datos de la película
$stmt = $pdo->prepare("SELECT * FROM peliculas WHERE id_pelicula = ?");
$stmt->execute([$id]);
$pelicula = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener categorías
$stmt = $pdo->query("SELECT * FROM categorias");
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener categorías seleccionadas
$stmt = $pdo->prepare("SELECT id_categoria FROM pelicula_categoria WHERE id_pelicula = ?");
$stmt->execute([$id]);
$categorias_seleccionadas = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Película</title>
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
            <form action="proc_editar_pelicula.php" method="POST" enctype="multipart/form-data" id="peliculaForm" onsubmit="return validarFormulario()">
                <div id="mensajesError" class="alert alert-danger" style="display: none; margin-top: 20px; background-color: #2f0000; border-color: #e50914; color: white;"></div>
                <input type="hidden" name="id" value="<?php echo $pelicula['id_pelicula']; ?>">
                
                <div class="mb-3">
                    <label for="title" class="form-label">Título</label>
                    <input type="text" class="form-control" id="title" name="title" 
                           value="<?php echo htmlspecialchars($pelicula['title']); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="poster" class="form-label">Poster Actual</label>
                    <img src="../<?php echo $pelicula['poster']; ?>" alt="Poster actual" style="max-width: 200px;">
                    <input type="file" class="form-control mt-2" id="poster" name="poster" accept="image/*">
                </div>
                
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required>
                        <?php echo htmlspecialchars($pelicula['descripcion']); ?>
                    </textarea>
                </div>
                
                <div class="mb-3">
                    <label for="autor" class="form-label">Autor</label>
                    <input type="text" class="form-control" id="autor" name="autor" 
                           value="<?php echo htmlspecialchars($pelicula['autor']); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="fecha_lanzamiento" class="form-label">Fecha de Lanzamiento</label>
                    <input type="date" class="form-control" id="fecha_lanzamiento" name="fecha_lanzamiento" 
                           value="<?php echo $pelicula['fecha_lanzamiento']; ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="reparto" class="form-label">Reparto</label>
                    <input type="text" class="form-control" id="reparto" name="reparto" 
                           value="<?php echo htmlspecialchars($pelicula['reparto']); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="trailer" class="form-label">URL del Trailer</label>
                    <input type="url" class="form-control" id="trailer" name="trailer" 
                           value="<?php echo htmlspecialchars($pelicula['trailer']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Categorías</label>
                    <?php foreach ($categorias as $categoria): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="categorias[]" 
                                   value="<?php echo $categoria['id_categoria']; ?>" 
                                   id="categoria<?php echo $categoria['id_categoria']; ?>"
                                   <?php echo in_array($categoria['id_categoria'], $categorias_seleccionadas) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="categoria<?php echo $categoria['id_categoria']; ?>">
                                <?php echo $categoria['nombre_categoria']; ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <button type="submit" class="btn-nuevo">Guardar Cambios</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/validaciones.js"></script>
</body>
</html> 