<?php
session_start();
require_once '../bbdd/db.php';

// Obtener solicitudes pendientes
try {
    $stmt = $pdo->query("
        SELECT 
            rp.id_solicitud,
            rp.nombre,
            rp.email,
            rp.fecha_registro,
            rp.estado
        FROM registro_pendiente rp
        WHERE rp.estado = 'pendiente'
        ORDER BY rp.fecha_registro DESC
    ");
    $solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener usuarios registrados
    $stmt = $pdo->query("
        SELECT 
            u.id,
            u.nombre,
            u.email,
            u.fecha_registro,
            r.nombre_rol,
            r.id_rol
        FROM usuarios u
        LEFT JOIN roles r ON u.id_rol = r.id_rol
        ORDER BY u.fecha_registro DESC
    ");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Obtener roles disponibles
    $stmt = $pdo->query("
        SELECT 
            id_rol,
            nombre_rol
        FROM roles
        ORDER BY id_rol
    ");
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error al obtener los datos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/stylesAdmin.css">
</head>

<body class="text-white">
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <img src="../img/logo-grande.png" alt="" class="navbar-logo">
        <form class="d-flex" role="search" id="searchForm">
            <input class="form-control" type="search" placeholder="Buscar..." aria-label="Search" id="searchQuery">
        </form>
        
        <!-- Filtros en la navbar -->
        <div class="filter-container">
            <div class="d-flex align-items-center gap-3">
                <!-- Nuevo botón dropdown para filtrar por roles -->
                <div class="dropdown">
                    <button class="btn btn-outline-danger dropdown-toggle" type="button" id="roleFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-tag"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="roleFilterDropdown">
                        <li>
                            <a class="dropdown-item text-white" href="#" onclick="filterByRole('all')">
                                <i class="fas fa-users"></i> Todos los usuarios
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <?php foreach ($roles as $rol): ?>
                        <li>
                            <a class="dropdown-item text-white" href="#" onclick="filterByRole(<?php echo $rol['id_rol']; ?>)">
                                <i class="fas fa-user-tag"></i> <?php echo htmlspecialchars($rol['nombre_rol']); ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- Botón para limpiar todos los filtros -->
                <button class="btn btn-outline-danger" id="clearAllFilters" title="Limpiar todos los filtros">
                    <i class="fas fa-trash-alt"></i>
                </button>
                
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
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="mb-4">Gestión de Solicitudes de Registro</h2>
    <div id="tablaSolicitudes" class="table-responsive">
    <!-- Aquí se cargará la tabla de solicitudes -->
    </div>

    <h2 class="mt-5 mb-4">Gestión de Usuarios <span id="roleFilterIndicator"></span></h2>
    <div class="d-flex gap-3 mb-4">
        <button class="btn-nuevo" onclick="showUserModal()">
            <a>Nuevo Usuario</a>
        </button>
        <a href="./gestionAdmin.php" class="btn-nuevo" id="btnNuevaPelicula">Películas</a>
    </div>

    <div id="tablaUsuarios" class="table-responsive">
    <!-- Aquí se cargará la tabla de usuarios -->
    </div>

<!-- Modal para Editar Usuario -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    <input type="hidden" id="userId" name="userId">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña">
                    </div>
                    <div class="mb-3">
                        <label for="rol" class="form-label">Rol</label>
                        <select class="form-select" id="rol" name="rol" required>
                            <option value="">Seleccionar rol</option>
                            <?php foreach ($roles as $rol): ?>
                                <option value="<?php echo $rol['id_rol']; ?>">
                                    <?php echo htmlspecialchars($rol['nombre_rol']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarUsuario">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Confirmar Eliminación -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Confirmar Eliminar Usuario -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="js/usuarios.js"></script>
<script src="js/solicitudes.js"></script>
<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
</body>
</html>
