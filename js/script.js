// Cargar las películas desde el servidor
fetch('./php/movies.php')
  .then(response => response.json())
  .then(movies => {
    const topContainer = document.getElementById('top-container');
    movies.forEach((movie, index) => {
      const movieItem = document.createElement('div');
      movieItem.classList.add('top-item');

      const movieNumber = document.createElement('div');
      movieNumber.classList.add('top-number');
      movieNumber.textContent = index + 1; // El número refleja el orden de likes
      movieItem.appendChild(movieNumber);

      const movieImg = document.createElement('img');
      movieImg.src = movie.poster; // La ruta de la imagen viene de la base de datos
      movieImg.alt = movie.title;
      movieImg.classList.add('movie-poster');

      // Agregar evento click a la imagen
      movieImg.addEventListener('click', () => showMovieModal(movie));
      movieItem.appendChild(movieImg);
      topContainer.appendChild(movieItem);
    });
  })
  .catch(error => console.error('Error al cargar las películas:', error));

// Función para alternar el like
function toggleLike(movieId, likeBtn) {
  // Verificar si el usuario está logueado
  if (!userId) {
    Swal.fire({
      title: 'Necesitas iniciar sesión',
      text: 'Para dar like necesitas estar registrado',
      icon: 'warning',
      confirmButtonText: 'Iniciar sesión',
      showCancelButton: true,
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        // Abrir el modal de login
        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        loginModal.show();
      }
    });
    return;
  }

  const currentLikes = parseInt(likeBtn.textContent.match(/\d+/)[0], 10);
  likeBtn.disabled = true;

  const action = likeBtn.classList.contains('liked') ? 'remove' : 'add';

  fetch('./php/toggle_like.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ movieId, action }),
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      likeBtn.classList.toggle('liked');
      const newLikes = data.newLikes;
      likeBtn.innerHTML = `${likeBtn.classList.contains('liked') ? '❤️' : '🤍'} ${newLikes}`;
    } else {
      Swal.fire({
        title: 'Error',
        text: data.message || 'Error desconocido',
        icon: 'error'
      });
    }
  })
  .catch(error => {
    console.error('Error:', error);
    Swal.fire({
      title: 'Error',
      text: 'Error al procesar la solicitud',
      icon: 'error'
    });
  })
  .finally(() => {
    likeBtn.disabled = false;
  });
}

// Función para mostrar el modal con los detalles de la película
function showMovieModal(movie) {
  Swal.fire({
    title: '', // El título se maneja dentro del HTML
    html: `
      <div class="row g-3 align-items-top" style="height: 100%;">
        <!-- Imagen de la película -->
        <div class="col-md-4 d-flex justify-content-center">
          <img src="${movie.poster}" alt="${movie.title}" class="img-fluid rounded" style="max-height: 400px;">
        </div>
        <!-- Detalles de la película -->
        <div class="col-md-8 ps-4">
          <h1 class="card-title">${movie.title}</h1>
          <p class="card-text"><strong>Autor:</strong> ${movie.autor || 'No disponible'}</p>
          <p class="card-text"><strong>Reparto:</strong> ${movie.reparto || 'No disponible'}</p>
          <p class="card-text"><strong>Directores:</strong> ${movie.director || 'No disponible'}</p>
          <p class="card-text"><strong>Fecha de lanzamiento:</strong> ${movie.fecha_lanzamiento || 'No disponible'}</p>
          <p class="card-text"><strong>Descripción:</strong> ${movie.descripcion || 'Sin descripción disponible'}</p>
          <!-- Botón de trailer -->
          <button class="btn btn-primary swal-trailer-btn" onclick="showTrailerModal('${movie.trailer}', '${movie.title}')">Ver Trailer</button>
          <!-- Contenedor de likes -->
          <div class="likes-container mt-3">
            <button class="like-btn ${movie.userLiked ? 'liked' : ''}" data-movie-id="${movie.id}">
              <i class="fas fa-heart"></i> ${movie.likes}
            </button>
          </div>
        </div>
      </div>
    `,
    width: '80%', // Ancho del modal
    heightAuto: false, // Desactiva la altura automática para controlarla manualmente
    showConfirmButton: false, // Ocultamos el botón "OK"
    showCloseButton: true, // Mostramos el botón de cierre
    customClass: {
      container: 'swal-modal-container',
      popup: 'swal-popup-custom', // Clase personalizada para el popup
      closeButton: 'swal-close-button', // Clase personalizada para el botón de cierre
    },
    didOpen: (modal) => {
      // Manejar clics en el botón de like dentro del modal
      const likeBtn = modal.querySelector('.like-btn');
      if (likeBtn) {
        likeBtn.addEventListener('click', () => toggleLike(movie.id, likeBtn));
      }
    },
  });
}

// Función para mostrar el modal del trailer
function showTrailerModal(trailerUrl, movieTitle) {
  Swal.fire({
    title: `Trailer de ${movieTitle}`,
    html: `
      <div>
        <iframe src="${trailerUrl}" frameborder="0" allowfullscreen style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></iframe>
      </div>
    `,
    width: '80%',
    heightAuto: false,
    showConfirmButton: false, // Ocultamos el botón "OK"
    showCloseButton: true, // Mostramos el botón de cierre
    customClass: {
      container: 'swal-modal-container',
      popup: 'swal-popup-custom', // Clase personalizada para el popup
      closeButton: 'swal-close-button', // Clase personalizada para el botón de cierre
    },
    willClose: () => {
      // Al cerrar el modal del trailer, volvemos al modal de los detalles de la película
      fetch('./php/movies.php')
        .then(response => response.json())
        .then(movies => {
          const movie = movies.find(m => m.title === movieTitle);
          if (movie) {
            showMovieModal(movie);
          }
        })
        .catch(error => console.error('Error al cargar las películas:', error));
    },
  });
}