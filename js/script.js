document.addEventListener('DOMContentLoaded', () => {
    loadMovies();
    loadCategories();
});

function loadMovies(filters = {}) {
    fetch('./php/movies.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(filters)
    })
    .then(response => response.json())
    .then(data => {
        const topContainer = document.getElementById('top-container');
        const otherContainer = document.getElementById('other-container');

        // Limpiar contenedores
        topContainer.innerHTML = '';
        otherContainer.innerHTML = '';

        // Mostrar las 5 películas con más likes (con número)
        data.topMovies.forEach((movie, index) => {
            const movieItem = createMovieElement(movie, index + 1, true);
            topContainer.appendChild(movieItem);
        });

        // Mostrar el resto de las películas (sin número)
        data.otherMovies.forEach((movie, index) => {
            const movieItem = createMovieElement(movie, index + 1, false);
            otherContainer.appendChild(movieItem);
        });
    })
    .catch(error => console.error('Error al cargar las películas:', error));
}

function createMovieElement(movie, index, showNumber = true) {
    const movieItem = document.createElement('div');
    movieItem.classList.add('top-item');

    // Solo agregar el número si showNumber es true
    if (showNumber) {
        const movieNumber = document.createElement('div');
        movieNumber.classList.add('top-number');
        movieNumber.textContent = index;
        movieItem.appendChild(movieNumber);
    }

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
        title: '',
        html: `
            <div class="row g-3 align-items-top">
                <div class="col-md-4 d-flex justify-content-center">
                    <img src="${movie.poster}" alt="${movie.title}" class="img-fluid rounded" style="max-height: 400px;">
                </div>
                <div class="col-md-8 ps-4">
                    <h1 class="card-title">${movie.title}</h1>
                    <p class="card-text"><strong>Autor:</strong> ${movie.autor || 'No disponible'}</p>
                    <p class="card-text"><strong>Reparto:</strong> ${movie.reparto || 'No disponible'}</p>
                    <p class="card-text"><strong>Fecha de lanzamiento:</strong> ${movie.fecha_lanzamiento || 'No disponible'}</p>
                    <p class="card-text"><strong>Descripción:</strong> ${movie.descripcion || 'Sin descripción disponible'}</p>
                    <button class="btn btn-primary swal-trailer-btn" onclick="showTrailerModal('${movie.trailer}', '${movie.title}')">Ver Trailer</button>
                    <div class="likes-container mt-3">
                        <button class="like-btn ${movie.user_liked ? 'liked' : ''}" data-movie-id="${movie.id}">
                            <i class="fas fa-heart ${movie.user_liked ? 'text-danger' : ''}"></i> 
                            <span class="likes-count">${movie.likes}</span>
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
                likeBtn.addEventListener('click', () => toggleLike(movie.id, likeBtn));
            }
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

function showTrailerModal(trailerUrl, movieTitle) {
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
        showCloseButton: true
    });

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

    // Eliminar el código que agrega los filtros al DOM ya que ahora están en la navbar
    searchInput.addEventListener('input', function() {
        const searchQuery = this.value;
        
        if (!searchQuery.trim()) {
            loadMovies();
            return;
        }

        fetch(`proc/search.php?query=${encodeURIComponent(searchQuery)}`)
            .then(response => response.json())
            .then(data => {
                // Clear the container
                topContainer.innerHTML = '';
                
                // Create movie cards for each result
                data.forEach((movie, index) => {
                    const movieItem = document.createElement('div');
                    movieItem.classList.add('top-item');

                    // Agregar el número solo para los primeros 5 resultados
                    if (index < 5) {
                        const movieNumber = document.createElement('div');
                        movieNumber.classList.add('top-number');
                        movieNumber.textContent = index + 1;
                        movieItem.appendChild(movieNumber);
                    }

                    const movieImg = document.createElement('img');
                    movieImg.src = movie.poster;
                    movieImg.alt = movie.title;
                    movieImg.classList.add('movie-poster');
                    movieImg.addEventListener('click', () => showMovieModal(movie));
                    movieItem.appendChild(movieImg);

                    topContainer.appendChild(movieItem);
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

    // Estado de los filtros
    let activeFilters = new Set();

    // Event listeners para los botones de filtro
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            if (activeFilters.has(filter)) {
                activeFilters.delete(filter);
                this.classList.remove('active');
            } else {
                activeFilters.add(filter);
                this.classList.add('active');
            }

            // Construir objeto de filtros
            const filters = {
                liked: activeFilters.has('liked'),
                notLiked: activeFilters.has('not-liked')
            };

            loadMovies(filters);
        });
    });

    // Event listener para el botón de limpiar filtros
    document.getElementById('clearFilters').addEventListener('click', function() {
        // Limpiar los filtros activos
        activeFilters.clear();
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Limpiar el campo de búsqueda
        document.getElementById('searchQuery').value = '';
        
        // Recargar las películas
        loadMovies();
    });
});

function loadCategories() {
    fetch('./php/categories.php')
        .then(response => response.json())
        .then(categories => {
            const categoryList = document.getElementById('categoryList');
            categoryList.innerHTML = `
                <li><a class="dropdown-item" href="#" data-category-id="">Todas las categorías</a></li>
                <li><hr class="dropdown-divider"></li>
            `;
            
            categories.forEach(category => {
                const li = document.createElement('li');
                li.innerHTML = `
                    <a class="dropdown-item category-item" href="#" data-category-id="${category.id_categoria}">
                        <input type="checkbox" class="form-check-input me-2" id="cat-${category.id_categoria}">
                        <label for="cat-${category.id_categoria}">${category.nombre_categoria}</label>
                    </a>`;
                categoryList.appendChild(li);
            });

            // Event listener para los checkboxes de categorías
            document.querySelectorAll('.category-item').forEach(item => {
                item.addEventListener('click', (e) => {
                    e.preventDefault();
                    const checkbox = item.querySelector('input[type="checkbox"]');
                    checkbox.checked = !checkbox.checked;
                    
                    // Actualizar el texto del botón
                    updateCategoryButtonText();
                    
                    // Cargar películas con los filtros actuales
                    loadMovies(getActiveFilters());
                });
            });
        })
        .catch(error => console.error('Error cargando categorías:', error));
}

function updateCategoryButtonText() {
    const selectedCategories = getSelectedCategories();
    const categoryButton = document.getElementById('categoryDropdown');
    
    if (selectedCategories.length === 0) {
        categoryButton.innerHTML = '<i class="fas fa-tags"></i>';
    } else {
        categoryButton.innerHTML = `<i class="fas fa-tags"></i> (${selectedCategories.length})`;
    }
}

function getSelectedCategories() {
    return Array.from(document.querySelectorAll('.category-item input[type="checkbox"]:checked'))
        .map(checkbox => checkbox.closest('.category-item').dataset.categoryId);
}

function getActiveFilters() {
    const activeFilters = {};
    
    // Obtener categorías seleccionadas
    const selectedCategories = getSelectedCategories();
    if (selectedCategories.length > 0) {
        activeFilters.categories = selectedCategories;
    }
    
    // Añadir otros filtros activos
    document.querySelectorAll('.filter-btn.active').forEach(btn => {
        activeFilters[btn.dataset.filter] = true;
    });
    
    return activeFilters;
}

// Modificar el event listener para limpiar filtros
document.getElementById('clearFilters').addEventListener('click', function() {
    // Limpiar checkboxes de categorías
    document.querySelectorAll('.category-item input[type="checkbox"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    // Limpiar otros filtros
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Actualizar texto del botón de categorías
    updateCategoryButtonText();
    
    // Recargar películas sin filtros
    loadMovies({});
});
