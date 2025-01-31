fetch('./php/movies.php')  
  .then(response => response.json())
  .then(movies => {
    const topContainer = document.getElementById('top-container');
    
    movies.forEach((movie, index) => {
      const movieItem = document.createElement('div');
      movieItem.classList.add('top-item');

      const movieNumber = document.createElement('div');
      movieNumber.classList.add('top-number');
      movieNumber.textContent = index + 1;
      movieItem.appendChild(movieNumber);

      const movieLink = document.createElement('a');
      movieLink.href = `detalle.html?movie=${movie.id}`;
      
      const movieImg = document.createElement('img');
      movieImg.src = movie.poster;  // La ruta de la imagen viene de la base de datos
      movieImg.alt = movie.title;
      movieImg.classList.add('movie-poster');
      
      movieLink.appendChild(movieImg);
      movieItem.appendChild(movieLink);

      topContainer.appendChild(movieItem);
    });
  })
  .catch(error => console.error('Error al cargar las películas:', error));
