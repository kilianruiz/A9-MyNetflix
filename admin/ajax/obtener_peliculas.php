<?php
session_start();
require_once "../../bbdd/db.php";

// Configuración de la paginación
$registros_por_pagina = isset($_GET['registros']) ? (int)$_GET['registros'] : 5;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina_actual - 1) * $registros_por_pagina;

try {
    // Obtener el total de registros
    $total_registros_query = "SELECT COUNT(*) as total FROM peliculas";
    $stmt = $pdo->query($total_registros_query);
    $total_registros = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $total_paginas = ceil($total_registros / $registros_por_pagina);

    // Obtener películas con LIMIT
    $query = "SELECT * FROM peliculas LIMIT $inicio, $registros_por_pagina";
    $stmt = $pdo->query($query);
    $peliculas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Generar HTML de la tabla
    $html = '<table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Poster</th>
                <th>Descripción</th>
                <th>Autor</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>';

    foreach ($peliculas as $pelicula) {
        $html .= '<tr>
            <td>' . htmlspecialchars($pelicula['id_pelicula']) . '</td>
            <td>' . htmlspecialchars($pelicula['title']) . '</td>
            <td><img src="../' . htmlspecialchars($pelicula['poster']) . '" class="movie-image" alt="Poster"></td>
            <td class="description-cell">' . htmlspecialchars($pelicula['descripcion']) . '</td>
            <td>' . htmlspecialchars($pelicula['autor']) . '</td>
            <td>' . htmlspecialchars($pelicula['fecha_lanzamiento']) . '</td>
            <td class="actions">
                <a href="#" class="btn-editar" data-id="' . $pelicula['id_pelicula'] . '">Editar</a>
                <a href="#" class="btn-eliminar" data-id="' . $pelicula['id_pelicula'] . '">Eliminar</a>
            </td>
        </tr>';
    }

    $html .= '</tbody></table>';

    // Agregar la paginación
    if ($total_paginas > 1) {
        $html .= '<div class="pagination">';
        
        // Primera y Anterior
        if ($pagina_actual > 1) {
            $html .= '<a href="#" data-pagina="1" data-registros="' . $registros_por_pagina . '">&laquo; Primera</a>';
            $html .= '<a href="#" data-pagina="' . ($pagina_actual - 1) . '" data-registros="' . $registros_por_pagina . '">Anterior</a>';
        }
        
        // Páginas numeradas
        for ($i = max(1, $pagina_actual - 2); $i <= min($total_paginas, $pagina_actual + 2); $i++) {
            $html .= '<a href="#" data-pagina="' . $i . '" data-registros="' . $registros_por_pagina . '"' . 
                    ($i == $pagina_actual ? ' class="active"' : '') . '>' . $i . '</a>';
        }
        
        // Siguiente y Última
        if ($pagina_actual < $total_paginas) {
            $html .= '<a href="#" data-pagina="' . ($pagina_actual + 1) . '" data-registros="' . $registros_por_pagina . '">Siguiente</a>';
            $html .= '<a href="#" data-pagina="' . $total_paginas . '" data-registros="' . $registros_por_pagina . '">Última &raquo;</a>';
        }
        
        $html .= '</div>';
    }

    echo $html;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} 