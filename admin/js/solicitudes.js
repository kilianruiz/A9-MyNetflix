document.addEventListener('DOMContentLoaded', function() {
    // Cargar tabla inicial
    cargarTablaSolicitudes();

    // Event listener para el selector de registros por página
    document.getElementById('registros').addEventListener('change', function() {
        cargarTablaSolicitudes(1, this.value);
    });
});

// Función para cargar la tabla de películas
function cargarTablaSolicitudes(pagina = 1, registros = 5) {
    fetch(`ajax/obtener_solicitudes.php?pagina=${pagina}&registros=${registros}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById('tablaSolicitudes').innerHTML = data;
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
            cargarTablaSolicitudes(pagina, registros);
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
            cargarTablaSolicitudes();
        } else {
            alert('Error al eliminar la película');
        }
    })
    .catch(error => console.error('Error:', error));
} 