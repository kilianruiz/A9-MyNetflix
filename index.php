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
        <button class="btn btn-outline-light" onclick="showLoginModal()">Login/Register</button>
      <?php endif; ?>
    </div>
  </nav>

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