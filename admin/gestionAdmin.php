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
            <select id="registros">
                <option value="2">2</option>
                <option value="5" selected>5</option>
                <option value="10">10</option>
                <option value="15">15</option>
            </select>
            <span>registros por página</span>
        </div>

        <a href="#" class="btn-nuevo" id="btnNuevaPelicula">Nueva Película</a>
        
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
</body>
</html>
