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
              <p class="card-text"><strong>Directores:</strong> ${movie.autor || 'No disponible'}</p>
              <p class="card-text"><strong>Fecha de lanzamiento:</strong> ${movie.fecha_lanzamiento || 'No disponible'}</p>
              <p class="card-text"><strong>Descripción:</strong> ${movie.descripcion || 'Sin descripción disponible'}</p>
              <!-- Botón de trailer -->
              <button class="btn btn-primary swal-trailer-btn" onclick="showTrailerModal('${movie.trailer}', '${movie.title}')">Ver Trailer</button>
              <!-- Contenedor de likes -->
              <div class="likes-container mt-3">
                <button class="like-btn" data-movie-id="${movie.id}">
                  <i class="fas fa-heart"></i> ${movie.likes}
                </button>
              </div>
            </div>
          </div>`,
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

    // Función para alternar el like
    function toggleLike(movieId, likeBtn) {
      const currentLikes = parseInt(likeBtn.textContent.match(/\d+/)[0], 10); // Obtener el número actual de likes
      const newLikes = currentLikes + (likeBtn.classList.contains('liked') ? -1 : 1); // Incrementar o decrementar

      // Alternar la clase "liked" para cambiar el estilo
      likeBtn.classList.toggle('liked');

      // Actualizar el texto del botón
      likeBtn.textContent = `${likeBtn.classList.contains('liked') ? '❤️' : '🤍'} ${newLikes}`;

      // Enviar solicitud al servidor para actualizar los likes
      fetch('./php/toggle_like.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ movieId, action: likeBtn.classList.contains('liked') ? 'add' : 'remove' }),
      })
        .then(response => response.json())
        .then(data => {
          if (!data.success) {
            alert('Error al actualizar los likes.');
          }
        })
        .catch(error => console.error('Error:', error));
    }
  })
  .catch(error => console.error('Error al cargar las películas:', error));

// Función para mostrar el modal del trailer
function showTrailerModal(trailerUrl, movieTitle) {
  const trailerModal = Swal.fire({
    title: `Trailer de ${movieTitle}`,
    html: `
      <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
        <iframe 
          src="${trailerUrl}" 
          frameborder="0" 
          allowfullscreen 
          style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
        ></iframe>
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

document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchQuery');
    const topContainer = document.getElementById('top-container');

    // Prevent form submission
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
    });

    // Add input event listener for real-time search
    searchInput.addEventListener('input', function() {
        const searchQuery = this.value;
        
        fetch(`proc/search.php?query=${encodeURIComponent(searchQuery)}`)
            .then(response => response.json())
            .then(data => {
                // Clear the container
                topContainer.innerHTML = '';
                
                // Create movie cards for each result
                data.forEach(movie => {
                    const movieCard = document.createElement('div');
                    movieCard.className = 'movie-card';
                    movieCard.innerHTML = `
                        <img src="${movie.poster}" alt="${movie.title}">
                        <h3>${movie.title}</h3>
                    `;
                    topContainer.appendChild(movieCard);
                });
                
                // If no results found
                if (data.length === 0) {
                    topContainer.innerHTML = '<p>No se encontraron resultados</p>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                topContainer.innerHTML = '<p>Error al buscar películas</p>';
            });
    });
});