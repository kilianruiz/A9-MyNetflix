function validarFormulario() {
    let hayErrores = false;
    
    // Limpiar mensajes de error anteriores
    const mensajesAnteriores = document.querySelectorAll('.error-mensaje');
    mensajesAnteriores.forEach(mensaje => mensaje.remove());
    
    // Función auxiliar para mostrar errores
    const mostrarError = (elemento, mensaje) => {
        hayErrores = true;
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-mensaje';
        errorDiv.textContent = mensaje;
        elemento.insertAdjacentElement('afterend', errorDiv);
        elemento.classList.add('is-invalid');
    };

    // Validar título
    const titulo = document.getElementById('title');
    if (titulo.value.trim().length < 2) {
        mostrarError(titulo, 'El título debe tener al menos 2 caracteres');
    }

    // Validar poster
    const poster = document.getElementById('poster');
    if (poster.files.length > 0) {
        const fileSize = poster.files[0].size / 1024 / 1024;
        const validExtensions = ['jpg', 'jpeg', 'png'];
        const fileExtension = poster.files[0].name.split('.').pop().toLowerCase();
        
        if (fileSize > 5) {
            mostrarError(poster, 'El poster no debe superar los 5MB');
        }
        if (!validExtensions.includes(fileExtension)) {
            mostrarError(poster, 'El poster debe ser una imagen JPG, JPEG o PNG');
        }
    }

    // Validar descripción
    const descripcion = document.getElementById('descripcion');
    if (descripcion.value.trim().length < 10) {
        mostrarError(descripcion, 'La descripción debe tener al menos 10 caracteres');
    }

    // Validar autor
    const autor = document.getElementById('autor');
    if (autor.value.trim().length < 3) {
        mostrarError(autor, 'El autor debe tener al menos 3 caracteres');
    }

    // Validar fecha
    const fecha = document.getElementById('fecha_lanzamiento');
    const fechaSeleccionada = new Date(fecha.value);
    const fechaActual = new Date();
    if (fechaSeleccionada > fechaActual) {
        mostrarError(fecha, 'La fecha no puede ser futura');
    }

    // Validar reparto
    const reparto = document.getElementById('reparto');
    if (reparto.value.trim().length < 3) {
        mostrarError(reparto, 'El reparto debe tener al menos 3 caracteres');
    }

    // Validar URL del trailer
    const trailer = document.getElementById('trailer');
    if (!trailer.value.trim().startsWith('http://') && !trailer.value.trim().startsWith('https://')) {
        mostrarError(trailer, 'La URL debe comenzar con http:// o https://');
    }

    // Validar categorías
    const categoriasContainer = document.querySelector('.form-check').parentNode;
    const categorias = document.querySelectorAll('input[name="categorias[]"]:checked');
    if (categorias.length === 0) {
        mostrarError(categoriasContainer, 'Debe seleccionar al menos una categoría');
    }

    // Si hay errores, mostrar el contenedor de mensajes de error
    const mensajesErrorContainer = document.getElementById('mensajesError');
    if (hayErrores) {
        mensajesErrorContainer.style.display = 'block';
        mensajesErrorContainer.innerHTML = 'Por favor, corrige los errores marcados en rojo.';
    } else {
        mensajesErrorContainer.style.display = 'none';
    }

    return !hayErrores;
}