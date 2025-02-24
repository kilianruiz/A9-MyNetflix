<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Poster</th>
            <th>Título</th>
            <th>Descripción</th>
            <th>Autor</th>
            <th>Fecha</th>
            <th>Categorías</th>
            <th>Likes</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($peliculas as $pelicula): ?>
            <tr>
                <td><?php echo htmlspecialchars($pelicula['id_pelicula']); ?></td>
                <td>
                    <?php 
                    $poster = $pelicula['poster'];
                    if (strpos($poster, './') === 0) {
                        $poster = substr($poster, 1);
                    }
                    ?>
                    <img src="..<?php echo htmlspecialchars($poster); ?>" alt="<?php echo htmlspecialchars($pelicula['title']); ?>" class="movie-image">
                </td>
                <td><?php echo htmlspecialchars($pelicula['title']); ?></td>
                <td class="description-cell"><?php echo htmlspecialchars($pelicula['descripcion']); ?></td>
                <td><?php echo htmlspecialchars($pelicula['autor']); ?></td>
                <td><?php echo htmlspecialchars($pelicula['fecha_lanzamiento']); ?></td>
                <td><?php echo htmlspecialchars($pelicula['categorias'] ?? ''); ?></td>
                <td><?php echo $pelicula['total_likes'] ?? '0'; ?></td>
                <td>
                    <div class="actions">
                        <a href="#" class="btn-editar" data-id="<?php echo $pelicula['id_pelicula']; ?>">Editar</a>
                        <a href="#" class="btn-eliminar" data-id="<?php echo $pelicula['id_pelicula']; ?>">Eliminar</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if (isset($total_paginas) && $total_paginas > 1): ?>
    <div class="pagination">
        <?php if ($pagina_actual > 1): ?>
            <a href="?pagina=<?php echo ($pagina_actual - 1); ?>&registros=<?php echo $registros_por_pagina; ?>">&laquo; Anterior</a>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <a href="?pagina=<?php echo $i; ?>&registros=<?php echo $registros_por_pagina; ?>" 
               class="<?php echo ($pagina_actual == $i) ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
        
        <?php if ($pagina_actual < $total_paginas): ?>
            <a href="?pagina=<?php echo ($pagina_actual + 1); ?>&registros=<?php echo $registros_por_pagina; ?>">Siguiente &raquo;</a>
        <?php endif; ?>
    </div>
<?php endif; ?> 