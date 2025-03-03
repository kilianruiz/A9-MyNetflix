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

    // Evento para cambiar el número de registros por página
    document.getElementById('registros').addEventListener('change', function () {
        cargarTablaUsuarios(1, this.value);
    });
});

/**
 * Función para mostrar el modal de edición/creación de usuarios
 */
function showUserModal(user = null) {
    const modalElement = document.getElementById('userModal');
    const modal = new bootstrap.Modal(modalElement);
    const form = document.getElementById('userForm');
    form.reset(); // Limpiar el formulario

    if (user) {
        // Si se está editando un usuario, poblar el formulario
        document.getElementById('userId').value = user.id;
        document.getElementById('nombre').value = user.nombre;
        document.getElementById('email').value = user.email;
        document.getElementById('rol').value = user.id_rol || '';
        document.getElementById('password').placeholder = 'Dejar en blanco para mantener la contraseña actual';
        document.getElementById('userModalLabel').textContent = 'Editar Usuario';
    } else {
        // Si es un nuevo usuario, limpiar el formulario
        document.getElementById('userId').value = '';
        document.getElementById('password').placeholder = 'Contraseña';
        document.getElementById('userModalLabel').textContent = 'Nuevo Usuario';
    }

    cargarRoles(); // Cargar roles dinámicamente
    modal.show(); // Mostrar el modal
}

/**
 * Función para cargar roles dinámicamente en el modal
 */
function cargarRoles() {
    fetch('../proc/getRoles.php') // Asegúrate de tener este endpoint en tu backend
        .then(response => response.json())
        .then(data => {
            const rolSelect = document.getElementById('rol');
            rolSelect.innerHTML = ''; // Limpiar opciones previas
            data.forEach(rol => {
                const option = document.createElement('option');
                option.value = rol.id_rol;
                option.textContent = rol.nombre_rol;
                rolSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error al cargar roles:', error));
}

/**
 * Función para guardar un usuario (crear o editar)
 */
function saveUser() {
    const userId = document.getElementById('userId').value;
    const data = {
        id: userId,
        nombre: document.getElementById('nombre').value,
        email: document.getElementById('email').value,
        password: document.getElementById('password').value,
        rol: document.getElementById('rol').value
    };

    const url = userId ? '../proc/editUser.php' : '../proc/createUser.php';

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: data.message
            }).then(() => {
                bootstrap.Modal.getInstance(document.getElementById('userModal')).hide(); // Cerrar el modal
                cargarTablaUsuarios(); // Recargar la tabla de usuarios
            });
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error', 'Hubo un problema con la conexión', 'error');
    });
}

/**
 * Función para eliminar un usuario
 */
function eliminarUsuario(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('../proc/deleteUser.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Éxito', 'Usuario eliminado correctamente', 'success');
                    cargarTablaUsuarios(); // Recargar la tabla de usuarios
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Hubo un problema con la conexión', 'error');
            });
        }
    });
}

/**
 * Función para configurar los botones de acciones (editar y eliminar)
 */
function configurarBotonesAcciones() {
    // Configurar botón Editar
    document.querySelectorAll('.btn-editar-usuario').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            fetch(`ajax/obtener_usuario.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showUserModal(data.user); // Mostrar el modal con los datos del usuario
                    } else {
                        Swal.fire('Error', data.message || 'No se encontraron datos del usuario', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Hubo un problema al obtener los datos del usuario', 'error');
                });
        });
    });

    // Configurar botón Eliminar
    document.querySelectorAll('.btn-eliminar-usuario').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            eliminarUsuario(id);
        });
    });
}

/**
 * Función para cargar la tabla de usuarios
 */
function cargarTablaUsuarios(pagina = 1, registros = 5) {
    fetch(`ajax/obtener_usuarios.php?pagina=${pagina}&registros=${registros}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById('tablaUsuarios').innerHTML = data;
            configurarBotonesAcciones(); // Configurar botones después de cargar la tabla
            configurarPaginacion();
        })
        .catch(error => console.error('Error:', error));
}

/**
 * Función para configurar los listeners de paginación
 */
function configurarPaginacion() {
    document.querySelectorAll('.pagination a').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const pagina = this.getAttribute('data-pagina');
            const registros = document.getElementById('registros').value;
            cargarTablaUsuarios(pagina, registros);
        });
    });
}