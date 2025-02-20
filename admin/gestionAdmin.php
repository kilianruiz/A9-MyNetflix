<?php
session_start();
require_once "../bbdd/db.php";

// Verificar si el usuario está logueado como admin
// if (!isset($_SESSION['user_id']) || $_SESSION['username'] != 'admin') {
//     header("Location: ../index.php");
//     exit();
// }

// Configuración de la paginación
$registros_por_pagina = isset($_GET['registros']) ? (int)$_GET['registros'] : 5;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina_actual - 1) * $registros_por_pagina;

// Obtener el total de registros
$total_registros_query = "SELECT COUNT(*) as total FROM peliculas";
$stmt = $pdo->query($total_registros_query);
$total_registros = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Obtener películas con LIMIT
$query = "SELECT * FROM peliculas LIMIT $inicio, $registros_por_pagina";
$stmt = $pdo->query($query);
$peliculas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Películas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/stylesAdmin.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <img src="../img/logo-grande.png" alt="" class="navbar-logo">
            <?php if(isset($_SESSION['username'])): ?>
                <div class="dropdown">
                    <button class="btn btn-outline-light dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-tie"></i> <?php echo $_SESSION['username']; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="../proc/logout.php">Cerrar sesión</a></li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">
        
        <div class="registros-selector">
            <label for="registros">Mostrar:</label>
            <select id="registros" onchange="cambiarRegistrosPorPagina(this.value)">
                <option value="2" <?php echo $registros_por_pagina == 2 ? 'selected' : ''; ?>>2</option>
                <option value="5" <?php echo $registros_por_pagina == 5 ? 'selected' : ''; ?>>5</option>
                <option value="10" <?php echo $registros_por_pagina == 10 ? 'selected' : ''; ?>>10</option>
                <option value="15" <?php echo $registros_por_pagina == 15 ? 'selected' : ''; ?>>15</option>
            </select>
            <span>registros por página</span>
        </div>

        <a href="nueva_pelicula.php" class="btn-nuevo">Nueva Película</a>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Poster</th>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Autor</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($peliculas as $pelicula): ?>
                <tr>
                    <td><?php echo $pelicula['id_pelicula']; ?></td>
                    <td>
                        <img src="../<?php echo $pelicula['poster']; ?>" alt="<?php echo $pelicula['title']; ?>" class="movie-image">
                    </td>
                    <td><?php echo $pelicula['title']; ?></td>
                    <td class="description-cell"><?php echo substr($pelicula['descripcion'], 0, 100) . '...'; ?></td>
                    <td><?php echo $pelicula['autor']; ?></td>
                    <td><?php echo $pelicula['fecha_lanzamiento']; ?></td>
                    <td class="actions">
                        <a href="editar_pelicula.php?id=<?php echo $pelicula['id_pelicula']; ?>" class="btn-editar">Editar</a>
                        <form action="eliminar_pelicula.php" method="POST" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $pelicula['id_pelicula']; ?>">
                            <button type="submit" class="btn-eliminar" onclick="return confirm('¿Está seguro de eliminar esta película?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- Agregar la paginación después de la tabla -->
        <div class="pagination">
            <?php if($pagina_actual > 1): ?>
                <a href="?pagina=1&registros=<?php echo $registros_por_pagina; ?>">&laquo; Primera</a>
                <a href="?pagina=<?php echo $pagina_actual - 1; ?>&registros=<?php echo $registros_por_pagina; ?>">Anterior</a>
            <?php endif; ?>
            
            <?php for($i = max(1, $pagina_actual - 2); $i <= min($total_paginas, $pagina_actual + 2); $i++): ?>
                <a href="?pagina=<?php echo $i; ?>&registros=<?php echo $registros_por_pagina; ?>" 
                   <?php echo ($i == $pagina_actual) ? 'class="active"' : ''; ?>>
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
            
            <?php if($pagina_actual < $total_paginas): ?>
                <a href="?pagina=<?php echo $pagina_actual + 1; ?>&registros=<?php echo $registros_por_pagina; ?>">Siguiente</a>
                <a href="?pagina=<?php echo $total_paginas; ?>&registros=<?php echo $registros_por_pagina; ?>">Última &raquo;</a>
            <?php endif; ?>
        </div>

        <script>
            function cambiarRegistrosPorPagina(valor) {
                window.location.href = '?registros=' + valor + '&pagina=1';
            }
        </script>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
