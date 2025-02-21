document.addEventListener('DOMContentLoaded', function() {
    // Cargar tabla inicial
    cargarTablaPeliculas();

    // Event listener para nueva película
    document.getElementById('btnNuevaPelicula').addEventListener('click', function(e) {
        e.preventDefault();
        window.location.href = 'nueva_pelicula.php';
    });

    // Event listener para el selector de registros por página
    document.getElementById('registros').addEventListener('change', function() {
        cargarTablaPeliculas(1, this.value);
    });
});

// Función para cargar la tabla de películas
function cargarTablaPeliculas(pagina = 1, registros = 5) {
    fetch(`ajax/obtener_peliculas.php?pagina=${pagina}&registros=${registros}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById('tablaPeliculas').innerHTML = data;
            // Agregar event listeners a los botones después de cargar la tabla
            configurarBotonesAcciones();
            configurarPaginacion();
        })
        .catch(error => console.error('Error:', error));
}

// Configurar los event listeners para la paginación
function configurarPaginacion() {
    document.querySelectorAll('.pagination a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const pagina = this.getAttribute('data-pagina');
            const registros = document.getElementById('registros').value;
            cargarTablaPeliculas(pagina, registros);
        });
    });
}

// Configurar los event listeners para los botones de acción
function configurarBotonesAcciones() {
    // Event listeners para botones de editar
    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            window.location.href = `editar_pelicula.php?id=${id}`;
        });
    });

    // Event listeners para botones de eliminar
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            if (confirm('¿Estás seguro de que deseas eliminar esta película?')) {
                eliminarPelicula(id);
            }
        });
    });
}

// Función para eliminar película
function eliminarPelicula(id) {
    const formData = new FormData();
    formData.append('id', id);

    fetch('ajax/eliminar_pelicula.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Recargar la tabla después de eliminar
            cargarTablaPeliculas();
        } else {
            alert('Error al eliminar la película');
        }
    })
    .catch(error => console.error('Error:', error));
} 