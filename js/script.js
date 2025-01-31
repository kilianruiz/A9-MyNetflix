// Obtén el parámetro de la URL
const urlParams = new URLSearchParams(window.location.search);
const movieId = urlParams.get('movie');

// Define las películas con datos estáticos para cada id
const movies = {
  1: {
    title: 'Breaking Bad',
    poster: './img/bb.webp',
    description: 'Serie de drama sobre un profesor de química que se convierte en fabricante de metanfetaminas.'
  },
  2: {
    title: 'La Casa de Papel',
    poster: './img/lcdp.webp',
    description: 'Un grupo de delincuentes lleva a cabo un asalto a la Real Casa de la Moneda de España.'
  },
  3: {
    title: 'Fast & Furious 8',
    poster: './img/ff8.webp',
    description: 'La octava entrega de la saga Fast & Furious, centrada en traiciones y carreras de autos.'
  },
  4: {
    title: 'Stranger Things',
    poster: './img/st.webp',
    description: 'Serie de ciencia ficción y terror que sigue a un grupo de niños enfrentándose a criaturas de otro mundo.'
  },
  5: {
    title: 'Prison Break',
    poster: './img/pb.png',
    description: 'La historia de la familia Shelby y su banda criminal en el post-guerra de Inglaterra.'
  }
};

// Usa el movieId para obtener los datos y mostrar la información
const movie = movies[movieId];
if (movie) {
  document.getElementById('moviePoster').src = movie.poster;
  document.getElementById('movieTitle').textContent = movie.title;
  document.getElementById('movieDescription').textContent = movie.description;
} else {
  // Si no se encuentra la película, muestra un mensaje de error
  document.getElementById('movieTitle').textContent = 'Película no encontrada';
  document.getElementById('movieDescription').textContent = 'Lo siento, esta película no está disponible.';
}

// Evento para el botón "Me Gusta"
document.getElementById('likeButton').addEventListener('click', function() {
  alert('¡Te ha gustado ' + movie.title + '!');
});
