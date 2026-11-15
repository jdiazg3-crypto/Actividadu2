<?php if (!empty($loadFailed)): ?>

    <p>
        No se pudo cargar el listado.
        Revisa la conexión a la base de datos.
    </p>

<?php elseif (empty($equipos)): ?>

    <p>
        <?= !empty($search)
            ? 'No se encontraron equipos para esa búsqueda.'
            : 'No hay equipos registrados todavía.' ?>
    </p>

<?php else: ?>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Técnico</th>
                <th>País</th>
                <th>Ciudad</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($equipos as $item): ?>
                <tr>
                    <?php
                    $columns = array(
                        'Nombre',
                        'Tecnico',
                        'Pais',
                        'Ciudad',
                        'Categoria',
                    );
                    ?>

                    <?php foreach ($columns as $column): ?>
                        <td>
                            <?= htmlspecialchars(
                                $item->{'get' . $column}(),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>
                    <?php endforeach; ?>

                    <td class="col-actions">
                        <a
                            class="btn btn-sm"
                            href="?route=equipos.show&amp;id=<?= urlencode($item->getId()) ?>"
                        >Ver</a>

                        <a
                            class="btn btn-warning btn-sm"
                            href="?route=equipos.edit&amp;id=<?= urlencode($item->getId()) ?>"
                        >Editar</a>

                        <a
                            class="btn btn-danger btn-sm"
                            href="?route=equipos.confirmDelete&amp;id=<?= urlencode($item->getId()) ?>"
                        >Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php endif; ?>