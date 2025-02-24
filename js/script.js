document.addEventListener('DOMContentLoaded', () => {
  loadMovies();
  setupLoginRegisterButtons();
});

function setupLoginRegisterButtons() {
  // Reemplazar el botón de Login/Register
  const loginBtn = document.querySelector('.btn-outline-light[data-bs-toggle="modal"]');
  if (loginBtn) {
    loginBtn.addEventListener('click', showLoginModal);
  }
}

function showLoginModal() {
  Swal.fire({
    title: 'Acceso',
    html: `
      <div class="nav nav-tabs mb-3" id="modalTabs">
        <button class="nav-link active" id="login-tab" onclick="switchTab('login')">
          Login
        </button>
        <button class="nav-link" id="register-tab" onclick="switchTab('register')">
          Register
        </button>
      </div>
      
      <div class="tab-content">
        <!-- Login Form -->
        <div class="tab-pane active" id="login-form">
          <form id="loginForm">
            <div class="mb-3">
              <input type="text" class="form-control" name="username" placeholder="Usuario" required>
            </div>
            <div class="mb-3">
              <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
            </div>
          </form>
        </div>
        
        <!-- Register Form -->
        <div class="tab-pane" id="register-form" style="display: none;">
          <form id="registerForm">
            <div class="mb-3">
              <input type="text" class="form-control" name="nombre" placeholder="Nombre" required>
            </div>
            <div class="mb-3">
              <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="mb-3">
              <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
            </div>
          </form>
        </div>
      </div>
    `,
    showConfirmButton: true,
    showCancelButton: true,
    confirmButtonText: 'Aceptar',
    cancelButtonText: 'Cancelar',
    didOpen: () => {
      // Inicializar estado del formulario
      window.currentTab = 'login';
    },
    preConfirm: () => {
      return handleFormSubmit();
    }
  });
}

function switchTab(tab) {
  // Remover clase active de todas las pestañas
  document.querySelectorAll('.nav-link').forEach(link => {
    link.classList.remove('active');
  });
  
  // Ocultar todos los formularios
  document.querySelectorAll('.tab-pane').forEach(pane => {
    pane.style.display = 'none';
  });
  
  // Activar la pestaña seleccionada
  if (tab === 'login') {
    document.getElementById('login-tab').classList.add('active');
    document.getElementById('login-form').style.display = 'block';
  } else {
    document.getElementById('register-tab').classList.add('active');
    document.getElementById('register-form').style.display = 'block';
  }
  
  // Actualizar pestaña actual
  window.currentTab = tab;
}

function handleFormSubmit() {
  const isLogin = window.currentTab === 'login';
  const form = document.getElementById(isLogin ? 'loginForm' : 'registerForm');
  const formData = new FormData(form);
  
  return fetch(isLogin ? './proc/procLogin.php' : './proc/procRegister.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      if (isLogin) {
        // Si es login exitoso, recargar la página
        window.location.reload();
      } else {
        // Si es registro exitoso, mostrar mensaje
        return Swal.fire({
          icon: 'success',
          title: '¡Solicitud enviada!',
          text: 'Tu solicitud de registro ha sido enviada. Por favor, espera la aprobación del administrador.',
          confirmButtonText: 'Aceptar'
        });
      }
    } else {
      throw new Error(data.message || 'Ha ocurrido un error');
    }
  })
  .catch(error => {
    Swal.showValidationMessage(error.message);
  });
}

function loadMovies() {
  fetch('./php/movies.php')
    .then(response => response.json())
    .then(movies => {
      const topContainer = document.getElementById('top-container');
      topContainer.innerHTML = ''; // Limpiar contenedor
      
      movies.forEach((movie, index) => {
        const movieItem = createMovieElement(movie, index);
        topContainer.appendChild(movieItem);
      });
    })
    .catch(error => console.error('Error al cargar las películas:', error));
}

function createMovieElement(movie, index) {
  const movieItem = document.createElement('div');
  movieItem.classList.add('top-item');

  const movieNumber = document.createElement('div');
  movieNumber.classList.add('top-number');
  movieNumber.textContent = index + 1;
  movieItem.appendChild(movieNumber);

  const movieImg = document.createElement('img');
  movieImg.src = movie.poster;
  movieImg.alt = movie.title;
  movieImg.classList.add('movie-poster');
  movieImg.addEventListener('click', () => showMovieModal(movie));
  movieItem.appendChild(movieImg);

  return movieItem;
}

function showMovieModal(movie) {
  Swal.fire({
    title: movie.title,
    html: `
      <div class="row g-3 align-items-top">
        <div class="col-md-4 d-flex justify-content-center">
          <img src="${movie.poster}" alt="${movie.title}" class="img-fluid rounded" style="max-height: 400px;">
        </div>
        <div class="col-md-8 ps-4">
          <p class="card-text"><strong>Autor:</strong> ${movie.autor || 'No disponible'}</p>
          <p class="card-text"><strong>Reparto:</strong> ${movie.reparto || 'No disponible'}</p>
          <p class="card-text"><strong>Fecha de lanzamiento:</strong> ${movie.fecha_lanzamiento || 'No disponible'}</p>
          <p class="card-text"><strong>Descripción:</strong> ${movie.descripcion || 'Sin descripción disponible'}</p>
          <button class="btn btn-primary swal-trailer-btn" onclick='showTrailerModal("${movie.trailer}", "${movie.title}", ${JSON.stringify(movie).replace(/'/g, "&#39;")})'>Ver Trailer</button>
          <div class="likes-container mt-3">
            <button class="like-btn ${movie.user_liked ? 'liked' : ''}" data-movie-id="${movie.id_pelicula}">
              <i class="fas fa-heart ${movie.user_liked ? 'text-danger' : ''}"></i>
              <span class="likes-count">${movie.likes || 0}</span>
            </button>
          </div>
        </div>
      </div>`,
    width: '80%',
    showConfirmButton: false,
    showCloseButton: true,
    didOpen: (modal) => {
      const likeBtn = modal.querySelector('.like-btn');
      if (likeBtn) {
        likeBtn.addEventListener('click', () => toggleLike(movie.id_pelicula, likeBtn));
      }
    }
  });
}

function showTrailerModal(trailerUrl, movieTitle, movie) {
  Swal.fire({
    title: `Trailer de ${movieTitle}`,
    html: `
      <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
        <iframe 
          src="${trailerUrl}" 
          frameborder="0" 
          allowfullscreen 
          style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
        </iframe>
      </div>
    `,
    width: '80%',
    showConfirmButton: false,
    showCloseButton: true,
    didClose: () => {
      showMovieModal(movie);
    }
  });
}

function toggleLike(movieId, likeBtn) {
  const action = likeBtn.classList.contains('liked') ? 'remove' : 'add';
  
  fetch('./php/toggle_like.php', {
    method: 'POST',
    headers: { 
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    body: JSON.stringify({ movieId, action })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      likeBtn.classList.toggle('liked');
      const likesCount = likeBtn.querySelector('.likes-count');
      if (likesCount) {
        likesCount.textContent = data.likes;
      }
      const heartIcon = likeBtn.querySelector('.fas.fa-heart');
      if (heartIcon) {
        heartIcon.classList.toggle('text-danger', data.userLiked);
      }
      // Recargar las películas para actualizar el orden
      loadMovies();
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: data.message || 'Debes iniciar sesión para dar like'
      });
    }
  })
  .catch(error => {
    console.error('Error:', error);
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Error al procesar la solicitud'
    });
  });
}