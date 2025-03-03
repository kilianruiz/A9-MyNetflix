document.addEventListener('DOMContentLoaded', function () {
    cargarTablaUsuarios();

    document.getElementById('btnGuardarUsuario').addEventListener('click', function (e) {
        e.preventDefault();
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

function showUserModal() {
    document.getElementById('userForm').reset();
    document.getElementById('userId').value = '';
    new bootstrap.Modal(document.getElementById('userModal')).show();
}

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
            Swal.fire('Éxito', data.message, 'success');
            document.getElementById('userModal').querySelector('.btn-close').click();
            cargarTablaUsuarios();
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Error', 'Hubo un problema con la conexión', 'error');
    });
}

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
                    cargarTablaUsuarios();
                } else {
                    Swal.fire('Error', 'No se pudo eliminar el usuario', 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Hubo un problema con la conexión', 'error');
            });
        }
    });
}

function configurarBotonesAcciones() {
    document.querySelectorAll('.btn-editar-usuario').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            window.location.href = `editUser.php?id=${id}`;
        });
    });

    document.querySelectorAll('.btn-eliminar-usuario').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            eliminarUsuario(id);
        });
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
