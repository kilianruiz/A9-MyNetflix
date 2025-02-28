<?php
    require_once '../bbdd/db.php';
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
    <link rel="stylesheet" href="../css/stylesSolicitudes.css">
</head>
<body class="bg-dark text-white">
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <img src="./img/logo-grande.png" alt="" class="navbar-logo">
            <form class="d-flex flex-grow-1" role="search" id="searchForm">
                <input class="form-control" type="search" placeholder="Buscar..." aria-label="Search" id="searchQuery">
                <button class="btn btn-outline-success" type="submit">Buscar</button>
            </form>
            <?php if(isset($_SESSION['username'])): ?>
                <div class="dropdown">
                    <button class="btn btn-outline-light dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user"></i> <?php echo $_SESSION['username']; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="./proc/logout.php">Cerrar sesión</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#loginModal">Login/Register</button>
            <?php endif; ?>
        </div>
    </nav>    

    <div class="container">
        <h2 class="mb-4">Gestión de Solicitudes de Registro</h2>
        
        <div id="tablaSolicitudes"></div>
            
        <h2 class="mt-5 mb-4">Gestión de Usuarios</h2>
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
                <i class="fas fa-user"></i>
                <input type="text" class="filtro-input" id="filtroAutor" placeholder="Buscar por usuario...">
            </div>

            <div class="filtro-grupo">
                <button class="limpiar-filtros" id="limpiarFiltros" title="Limpiar todos los filtros">
                    <i class="fas fa-broom"></i>
                </button>
            </div>
        </div>
        <button class="btn btn-success mb-3" onclick="showUserModal()">
            <i class="fas fa-plus"></i> Nuevo Usuario
        </button>
                    
        <div id="tablaUsuarios"></div>
        
    </div>

    <!-- Modal para Crear/Editar Usuario -->
    <div class="modal fade" id="userModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Usuario</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="userForm">
                        <input type="hidden" id="userId" name="id">
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
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="rol" class="form-label">Rol</label>
                            <select class="form-control" id="rol" name="rol" required>
                                <option value="2">Usuario</option>
                                <option value="1">Administrador</option>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/usuarios.js"></script>
    <script src="js/solicitudes.js"></script>
</body>
</html> 