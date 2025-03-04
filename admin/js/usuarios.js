document.addEventListener('DOMContentLoaded', function () {
    cargarTablaUsuarios();

    // Evento para guardar un usuario
    document.getElementById('btnGuardarUsuario').addEventListener('click', function (e) {
        e.preventDefault();
        saveUser();
    });

    // Evento para abrir el modal de nuevo usuario
    document.getElementById('btnNuevoUsuario').addEventListener('click', function (e) {
        e.preventDefault();
        showUserModal();
    });
});

/**
 * Mostrar el modal de edición/creación de usuarios
 */
function showUserModal(user = null) {
    const modalElement = document.getElementById('userModal');
    const modal = new bootstrap.Modal(modalElement);
    const form = document.getElementById('userForm');
    form.reset();

    if (user) {
        document.getElementById('userId').value = user.id;
        document.getElementById('nombre').value = user.nombre;
        document.getElementById('email').value = user.email;
        document.getElementById('rol').value = user.id_rol || '';
        document.getElementById('password').placeholder = 'Dejar en blanco para mantener la contraseña actual';
        document.getElementById('userModalLabel').textContent = 'Editar Usuario';
    } else {
        document.getElementById('userId').value = '';
        document.getElementById('password').placeholder = 'Contraseña';
        document.getElementById('userModalLabel').textContent = 'Nuevo Usuario';
    }

    modal.show();
}

/**
 * Cargar la tabla de usuarios y configurar los botones de acciones
 */
function cargarTablaUsuarios(pagina = 1, registros = 5) {
    fetch(`ajax/obtener_usuarios.php?pagina=${pagina}&registros=${registros}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById('tablaUsuarios').innerHTML = data;
            configurarBotonesAcciones();
        })
        .catch(error => console.error('Error:', error));
}