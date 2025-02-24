$(document).ready(function() {
    let ordenLikes = 'none';
    let filtros = {
        titulo: '',
        autor: '',
        fecha: '',
        ordenLikes: 'none'
    };

    // Función para actualizar la tabla
    function actualizarTabla() {
        $.ajax({
            url: 'proc/filtrar_peliculas.php',
            type: 'POST',
            data: filtros,
            success: function(response) {
                $('#tablaPeliculas').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    // Event listeners para los filtros
    $('#filtroTitulo').on('input', function() {
        filtros.titulo = $(this).val();
        actualizarTabla();
    });

    $('#filtroAutor').on('input', function() {
        filtros.autor = $(this).val();
        actualizarTabla();
    });

    $('#filtroFecha').on('change', function() {
        filtros.fecha = $(this).val();
        actualizarTabla();
    });

    $('#ordenLikes').on('click', function() {
        const $icon = $(this).find('i');
        
        switch(ordenLikes) {
            case 'none':
                ordenLikes = 'asc';
                $icon.removeClass('fa-sort').addClass('fa-sort-up');
                break;
            case 'asc':
                ordenLikes = 'desc';
                $icon.removeClass('fa-sort-up').addClass('fa-sort-down');
                break;
            case 'desc':
                ordenLikes = 'none';
                $icon.removeClass('fa-sort-down').addClass('fa-sort');
                break;
        }
        
        filtros.ordenLikes = ordenLikes;
        actualizarTabla();
    });

    function aplicarFiltros(pagina = 1) {
        const titulo = $('#filtroTitulo').val();
        const autor = $('#filtroAutor').val();
        const fecha = $('#filtroFecha').val();
        const categoria = $('#filtroCategoria').val();
        const ordenLikes = $('#ordenLikes').data('orden');
        const registros = $('#registros').val();

        const formData = new FormData();
        formData.append('titulo', titulo);
        formData.append('autor', autor);
        formData.append('fecha', fecha);
        formData.append('categoria', categoria);
        formData.append('ordenLikes', ordenLikes);
        formData.append('registros', registros);
        formData.append('pagina', pagina);

        fetch('proc/filtrar_peliculas.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(html => {
            $('#tablaPeliculas').html(html);
            configurarBotonesAcciones();
            configurarPaginacionFiltrada();
        })
        .catch(error => console.error('Error:', error));
    }

    function configurarPaginacionFiltrada() {
        $('.pagination a').each(function() {
            $(this).on('click', function(e) {
                e.preventDefault();
                const pagina = $(this).data('pagina');
                aplicarFiltros(pagina);
            });
        });
    }

    $('#filtroCategoria').on('change', aplicarFiltros);

    $('#limpiarFiltros').on('click', function() {
        $('#filtroTitulo').val('');
        $('#filtroAutor').val('');
        $('#filtroFecha').val('');
        $('#filtroCategoria').val('');
        $('#ordenLikes').data('orden', 'none').find('i').removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');
        aplicarFiltros();
    });

    // Event listener para el selector de registros por página
    $('#registros').on('change', function() {
        aplicarFiltros(1);
    });
}); 