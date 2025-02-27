document.addEventListener('DOMContentLoaded', function () {
    cargarTablaUsuarios();

    // Asegurarse de que el botón de guardar tiene el evento `click`
    document.getElementById('btnGuardarUsuario').addEventListener('click', function (e) {
        e.preventDefault(); // Evita que se recargue la página
        saveUser();
    });

    document.getElementById('btnNuevoUsuario').addEventListener('click', function (e) {
        e.preventDefault();
        showUserModal();
    });

    document.getElementById('registros').addEventListener('change', function () {
        cargarTablaUsuarios(1, this.value);
    });
});

// Función para mostrar el modal de usuario
function showUserModal() {
    document.getElementById('userForm').reset(); // Limpiar el formulario
    document.getElementById('userId').value = ''; // Resetear el ID
    new bootstrap.Modal(document.getElementById('userModal')).show();
}

// Función para guardar usuario (crear o editar)
function saveUser() {
    let form = document.getElementById('userForm');
    let formData = new FormData(form);

    console.log('Enviando datos del formulario:', Object.fromEntries(formData));

    fetch('./createUser.php', { // Asegúrate de que la ruta sea correcta
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Respuesta del servidor:', data); // Verifica la respuesta del backend

        if (data.success) {
            Swal.fire('Éxito', data.message, 'success');
            document.getElementById('userModal').querySelector('.btn-close').click();
            cargarTablaUsuarios();
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error en la petición:', error);
        Swal.fire('Error', 'Hubo un problema con la conexión', 'error');
    });
}

// Función para cargar la tabla de usuarios
function cargarTablaUsuarios(pagina = 1, registros = 5) {
    fetch(`ajax/obtener_usuarios.php?pagina=${pagina}&registros=${registros}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById('tablaUsuarios').innerHTML = data;
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
            cargarTablaUsuarios(pagina, registros);
        });
    });
}

// Configurar los event listeners para los botones de acción
function configurarBotonesAcciones() {
    // Event listeners para botones de editar
    document.querySelectorAll('.btn-editar-usuario').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            window.location.href = `editUser.php?id=${id}`;
        });
    });

    // Event listeners para botones de eliminar
    document.querySelectorAll('.btn-eliminar-usuario').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
                eliminarUsuario(id);
            }
        });
    });
}

// Función para eliminar usuario
function eliminarUsuario(id) {
    const formData = new FormData();
    formData.append('id', id);

    fetch('ajax/deleteUser.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Éxito', 'Usuario eliminado correctamente', 'success');
            cargarTablaUsuarios();
        } else {
            Swal.fire('Error', 'No se pudo eliminar el usuario', 'error');
        }
    })
    .catch(error => console.error('Error:', error));
}
