<?php
require_once "../../bbdd/db.php";

$titulo = $_POST['titulo'] ?? '';
$autor = $_POST['autor'] ?? '';
$fecha = $_POST['fecha'] ?? '';
$ordenLikes = $_POST['ordenLikes'] ?? 'none';
$categoria = $_POST['categoria'] ?? '';
$registros_por_pagina = max(1, isset($_POST['registros']) ? (int)$_POST['registros'] : 5);
$pagina_actual = max(1, isset($_POST['pagina']) ? (int)$_POST['pagina'] : 1);
$inicio = max(0, ($pagina_actual - 1) * $registros_por_pagina);

// Query para contar el total de registros filtrados
$count_query = "SELECT COUNT(DISTINCT p.id_pelicula) as total 
                FROM peliculas p 
                LEFT JOIN pelicula_categoria pc ON p.id_pelicula = pc.id_pelicula
                LEFT JOIN categorias c ON pc.id_categoria = c.id_categoria
                WHERE 1=1";

// Query base con JOIN para likes y categorías
$query = "SELECT p.*, 
          COUNT(DISTINCT l.id_like_usuario) as total_likes,
          GROUP_CONCAT(DISTINCT c.nombre_categoria ORDER BY c.nombre_categoria ASC SEPARATOR ', ') as categorias
          FROM peliculas p 
          LEFT JOIN likes l ON p.id_pelicula = l.pelicula_id 
          LEFT JOIN pelicula_categoria pc ON p.id_pelicula = pc.id_pelicula
          LEFT JOIN categorias c ON pc.id_categoria = c.id_categoria
          WHERE 1=1";

// Aplicar filtros a ambas queries
$params = array(); // Array para almacenar los parámetros

if (!empty($titulo)) {
    $filtro = " AND p.title LIKE :titulo";
    $query .= $filtro;
    $count_query .= $filtro;
    $params[':titulo'] = "%$titulo%";
}

if (!empty($autor)) {
    $filtro = " AND p.autor LIKE :autor";
    $query .= $filtro;
    $count_query .= $filtro;
    $params[':autor'] = "%$autor%";
}

if (!empty($fecha)) {
    $filtro = " AND DATE(p.fecha_lanzamiento) = :fecha";
    $query .= $filtro;
    $count_query .= $filtro;
    $params[':fecha'] = $fecha;
}

if (!empty($categoria)) {
    $filtro = " AND c.id_categoria = :categoria";
    $query .= $filtro;
    $count_query .= $filtro;
    $params[':categoria'] = $categoria;
}

$query .= " GROUP BY p.id_pelicula";

if ($ordenLikes !== 'none') {
    $query .= " ORDER BY total_likes " . ($ordenLikes === 'asc' ? 'ASC' : 'DESC');
}

// Agregar LIMIT para paginación
$query .= " LIMIT :inicio, :registros";

// Preparar y ejecutar query para contar registros
$stmt_count = $pdo->prepare($count_query);
$stmt = $pdo->prepare($query);

// Bind parameters usando el array de parámetros
foreach ($params as $key => $value) {
    $stmt_count->bindValue($key, $value);
    $stmt->bindValue($key, $value);
}

// Bind parameters específicos para la query principal
$stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
$stmt->bindValue(':registros', $registros_por_pagina, PDO::PARAM_INT);

// Ejecutar queries
$stmt_count->execute();
$total_registros = $stmt_count->fetch(PDO::FETCH_ASSOC)['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

$stmt->execute();
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
            <th>Categorías</th>
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
        <td>' . htmlspecialchars($pelicula['categorias'] ?? 'Sin categorías') . '</td>
        <td class="actions">
            <a href="#" class="btn-editar" data-id="' . $pelicula['id_pelicula'] . '">Editar</a>
            <a href="#" class="btn-eliminar" data-id="' . $pelicula['id_pelicula'] . '">Eliminar</a>
        </td>
    </tr>';
}

$html .= '</tbody></table>';

// Agregar paginación
if ($total_paginas > 1) {
    $html .= '<div class="pagination">';
    
    // Primera y Anterior
    if ($pagina_actual > 1) {
        $html .= '<a href="#" data-pagina="1">&laquo; Primera</a>';
        $html .= '<a href="#" data-pagina="' . ($pagina_actual - 1) . '">Anterior</a>';
    }
    
    // Páginas numeradas
    for ($i = max(1, $pagina_actual - 2); $i <= min($total_paginas, $pagina_actual + 2); $i++) {
        $html .= '<a href="#" data-pagina="' . $i . '"' . 
                ($i == $pagina_actual ? ' class="active"' : '') . '>' . $i . '</a>';
    }
    
    // Siguiente y Última
    if ($pagina_actual < $total_paginas) {
        $html .= '<a href="#" data-pagina="' . ($pagina_actual + 1) . '">Siguiente</a>';
        $html .= '<a href="#" data-pagina="' . $total_paginas . '">Última &raquo;</a>';
    }
    
    $html .= '</div>';
}

echo $html; 