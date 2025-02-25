let userModal;

document.addEventListener('DOMContentLoaded', function() {
    userModal = new bootstrap.Modal(document.getElementById('userModal'));
});

function showUserModal(userId = null) {
    const form = document.getElementById('userForm');
    form.reset();
    document.getElementById('userId').value = '';
    document.getElementById('userModalTitle').textContent = 'Nuevo Usuario';
    
    if (userId) {
        // Cargar datos del usuario para edición
        fetch(`../proc/getUserData.php?id=${userId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('userId').value = data.user.id;
                    document.getElementById('nombre').value = data.user.nombre;
                    document.getElementById('email').value = data.user.email;
                    document.getElementById('rol').value = data.user.id_rol;
                    document.getElementById('userModalTitle').textContent = 'Editar Usuario';
                }
            });
    }
    
    userModal.show();
}

function saveUser() {
    const form = document.getElementById('userForm');
    const formData = new FormData(form);

    fetch('../proc/saveUser.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: data.message,
                background: '#212529',
                color: '#fff'
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message,
                background: '#212529',
                color: '#fff'
            });
        }
    });
}

function deleteUser(userId) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        background: '#212529',
        color: '#fff'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('../proc/deleteUser.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: userId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Eliminado!',
                        text: data.message,
                        background: '#212529',
                        color: '#fff'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message,
                        background: '#212529',
                        color: '#fff'
                    });
                }
            });
        }
    });
}

function editUser(userId) {
    showUserModal(userId);
} 