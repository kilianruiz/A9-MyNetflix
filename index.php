<?php

  session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="./css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <title>Netflix</title>
</head>
<body>
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

  <!-- Modal -->
  <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="loginModalLabel">Login</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <ul class="nav nav-tabs" id="loginTab" role="tablist">
            <li class="nav-item" role="presentation">
              <a class="nav-link active" id="login-tab" data-bs-toggle="tab" href="#login" role="tab" aria-controls="login" aria-selected="true">Login</a>
            </li>
            <li class="nav-item" role="presentation">
              <a class="nav-link" id="register-tab" data-bs-toggle="tab" href="#register" role="tab" aria-controls="register" aria-selected="false">Register</a>
            </li>
          </ul>
          <div class="tab-content mt-3" id="loginTabContent">
            <!-- Login Form -->
            <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
              <form action="./proc/procLogin.php" method="POST">
                <div class="mb-3">
                  <label for="username" class="for m-label">Usuario</label>
                  <input type="text" class="form-control" name="username" id="username" placeholder="Enter user">
                </div>
                <div class="mb-3">
                  <label for="password" class="form-label">Password</label>
                  <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
              </form>
            </div>
            <!-- Register Form -->
            <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
              <form action="./proc/procRegister.php" method="POST">
                <div class="mb-3">
                  <label for="registerName" class="form-label">Nombre</label>
                  <input type="text" class="form-control" name="nombre" id="registerName" placeholder="Enter name">
                </div>
                <div class="mb-3">
                  <label for="registerEmail" class="form-label">Email address</label>
                  <input type="email" class="form-control" name="email" id="registerEmail" placeholder="Enter email">
                </div>
                <div class="mb-3">
                  <label for="registerPassword" class="form-label">Password</label>
                  <input type="password" class="form-control" name="password" id="registerPassword" placeholder="Password">
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="pelis">
    <h4>Top 5 series/peliculas más gustadas</h4>
    <div class="top-container" id="top-container">
      <!-- Las películas se cargarán aquí dinámicamente -->
    </div>
  </div>

  <script src="js/script.js"></script> 
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
