document.addEventListener('DOMContentLoaded', function () {
    // Cargar tabla inicial
    cargarTablaSolicitudes();
    cargarTablaUsuarios();

    // Event listener para el selector de registros por página
    document.getElementById('registros').addEventListener('change', function () {
        cargarTablaSolicitudes(1, this.value);
    });
});

// Función para cargar la tabla de solicitudes
function cargarTablaSolicitudes(pagina = 1, registros = 5) {
    fetch(`ajax/obtener_solicitudes.php?pagina=${pagina}&registros=${registros}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById('tablaSolicitudes').innerHTML = data;
            // Configurar botones y paginación después de cargar la tabla
            configurarBotonesAcciones();
            configurarPaginacion();
        })
        .catch(error => console.error('Error al cargar la tabla de solicitudes:', error));
}

// Función para cargar la tabla de usuarios
function cargarTablaUsuarios() {
    fetch('ajax/obtener_usuarios.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('tablaUsuarios').innerHTML = data;
        })
        .catch(error => console.error('Error al cargar la tabla de usuarios:', error));
}

// Configurar los event listeners para la paginación
function configurarPaginacion() {
    document.querySelectorAll('.pagination a').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const pagina = this.getAttribute('data-pagina');
            const registros = document.getElementById('registros').value;
            cargarTablaSolicitudes(pagina, registros);
        });
    });
}

// Configurar los event listeners para los botones de acción
function configurarBotonesAcciones() {
    // Event listeners para botones de aceptar solicitud
    document.querySelectorAll('.btn-aceptar').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            if (id) {
                procesarSolicitud(id, 'aprobada');
            } else {
                Swal.fire('Error', 'ID de solicitud no encontrado', 'error');
            }
        });
    });

    // Event listeners para botones de rechazar solicitud
    document.querySelectorAll('.btn-rechazar').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            if (id) {
                eliminarSolicitud(id);
            } else {
                Swal.fire('Error', 'ID de solicitud no encontrado', 'error');
            }
        });
    });
}

// Función para procesar solicitudes (aceptar)
function procesarSolicitud(id, estado) {
    const mensaje = estado === 'aprobada' ? 'aprobar' : 'rechazar';

    Swal.fire({
        title: `¿Estás seguro?`,
        text: `¿Deseas ${mensaje} esta solicitud?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: estado === 'aprobada' ? '#28a745' : '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: estado === 'aprobada' ? 'Aprobar' : 'Rechazar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('../proc/procesarSolicitud.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id, estado: estado })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Éxito', data.message, 'success');
                        cargarTablaSolicitudes(); // Recargar la tabla de solicitudes
                        cargarTablaUsuarios(); // Recargar la tabla de usuarios
                    } else {
                        Swal.fire('Error', data.message || 'Hubo un problema al procesar la solicitud', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'Hubo un problema con la conexión', 'error');
                    console.error('Error en la solicitud:', error);
                });
        }
    });
}

// Función para eliminar solicitudes (rechazar)
function eliminarSolicitud(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción eliminará la solicitud permanentemente.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('../proc/eliminar_solicitud.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Éxito', data.message, 'success');
                        cargarTablaSolicitudes(); // Recargar la tabla de solicitudes
                    } else {
                        Swal.fire('Error', data.message || 'Hubo un problema al eliminar la solicitud', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'Hubo un problema con la conexión', 'error');
                    console.error('Error en la solicitud:', error);
                });
        }
    });
}