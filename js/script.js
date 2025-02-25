document.addEventListener('DOMContentLoaded', () => {
    loadMovies();
});

function loadMovies() {
    fetch('./php/movies.php')
        .then(response => response.json())
        .then(data => {
            const topContainer = document.getElementById('top-container');
            const otherContainer = document.getElementById('other-container');

            // Limpiar contenedores
            topContainer.innerHTML = '';
            otherContainer.innerHTML = '';

            // Mostrar las 5 películas con más likes (con número)
            data.topMovies.forEach((movie, index) => {
                const movieItem = createMovieElement(movie, index + 1, true); // Agregar número
                topContainer.appendChild(movieItem);
            });

            // Mostrar el resto de las películas (sin número)
            data.otherMovies.forEach((movie, index) => {
                const movieItem = createMovieElement(movie, index + 1, false); // No agregar número
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
}