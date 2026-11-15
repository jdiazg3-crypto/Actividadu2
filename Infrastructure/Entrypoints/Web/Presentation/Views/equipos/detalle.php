<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/menu.php'; ?>
<?php require __DIR__ . '/mensajes.php'; ?>

<h1>Detalle del equipo</h1>

<table class="detail-table">
    <tr>
        <th>ID</th>
        <td>
            <?= htmlspecialchars($equipo->getId(), ENT_QUOTES, 'UTF-8') ?>
        </td>
    </tr>

    <?php foreach ($fields as $key => $definition): ?>
        <tr>
            <th><?= $definition['label'] ?></th>
            <td>
                <?= htmlspecialchars(
                    (string) ($equipo->{'get' . ucfirst($key)}() ?? ''),
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<p>
    <a class="btn" href="?route=equipos.index">
        Volver al listado
    </a>

    <a
        class="btn btn-warning"
        href="?route=equipos.edit&amp;id=<?= urlencode($equipo->getId()) ?>"
    >Editar</a>
</p>

<?php require __DIR__ . '/../layouts/footer.php'; ?>