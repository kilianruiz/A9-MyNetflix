<?php
session_start(); 

require_once '../bbdd/db.php';

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
    <style>
        /* Cambiar color del icono cuando se pasa el cursor */
        .navbar .fa-user-tie:hover {
            color: white; /* Cambiar a blanco al pasar el cursor */
        }
    </style>
</head>
<body data-user-logged="<?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>" 
      data-username="<?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : ''; ?>">
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <img src="../img/logo-grande.png" alt="" class="navbar-logo">
            
            <!-- Mover los filtros aquí -->
            <div class="filter-container">
                <div class="d-flex align-items-center gap-3">
        
            <?php if(isset($_SESSION['username'])): ?>
                <div class="dropdown">
                    <button class="btn btn-outline-light dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-tie"></i> <?php echo $_SESSION['username']; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="../proc/logout.php">Cerrar sesión</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#loginModal">Login/Register</button>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">
        <div class="registros-selector">
            <label for="registros">Mostrar:</label>
            <select id="registros">
                <option value="2">2</option>
                <option value="5" selected>5</option>
                <option value="10">10</option>
                <option value="15">15</option>
            </select>
            <span>registros por página</span>
        </div>

        <div class="filtros-container">
            <div class="filtro-grupo">
                <i class="fas fa-film"></i>
                <input type="text" class="filtro-input" id="filtroTitulo" placeholder="Buscar por título de película...">
            </div>
            
            <div class="filtro-grupo">
                <i class="fas fa-user"></i>
                <input type="text" class="filtro-input" id="filtroAutor" placeholder="Buscar por nombre del director...">
            </div>
            
            <div class="filtro-grupo">
                <i class="fas fa-calendar"></i>
                <input type="date" class="filtro-input" id="filtroFecha" placeholder="Seleccionar fecha de lanzamiento">
            </div>
            
            <div class="filtro-grupo">
                <i class="fas fa-tags"></i>
                <select class="filtro-input" id="filtroCategoria">
                    <option value="">Seleccionar categoría...</option>
                    <?php
                    $query = "SELECT * FROM categorias ORDER BY nombre_categoria";
                    $categorias = $pdo->query($query)->fetchAll();
                    foreach ($categorias as $categoria) {
                        echo "<option value='" . $categoria['id_categoria'] . "'>" . htmlspecialchars($categoria['nombre_categoria']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="filtro-grupo">
                <i class="fas fa-heart"></i>
                <button class="orden-likes" id="ordenLikes" data-orden="none" title="Ordenar por likes">
                    <i class="fas fa-sort"></i>
                </button>
            </div>

            <div class="filtro-grupo">
                <button class="limpiar-filtros" id="limpiarFiltros" title="Limpiar todos los filtros">
                    <i class="fas fa-broom"></i>
                </button>
            </div>
        </div>

        <a href="#" class="btn-nuevo" id="btnNuevaPelicula">Nueva Película</a>
        <a href="./gestion_solicitudes.php" class="btn-nuevo" id="">Usuarios</a>
        
        <div id="tablaPeliculas">
            <!-- La tabla se cargará aquí dinámicamente -->
        </div>
    </div>

    <!-- Modal para Nueva/Editar Película -->
    <div class="modal fade" id="peliculaModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Nueva Película</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- El formulario se cargará aquí dinámicamente -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin.js"></script>
    <script src="js/filtros.js"></script>
</body>
</html>
