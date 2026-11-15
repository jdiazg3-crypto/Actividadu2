<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/menu.php'; ?>
<?php require __DIR__ . '/mensajes.php'; ?>

<h1>Eliminar equipo</h1>

<p>
    ¿Confirmas que deseas eliminar a
    <strong>
        <?= htmlspecialchars($equipo->getNombre(), ENT_QUOTES, 'UTF-8') ?>
    </strong>?
</p>

<p>Esta operación elimina el registro de forma permanente.</p>

<form method="POST" action="?route=equipos.delete">
    <input
        type="hidden"
        name="csrf"
        value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>"
    >

    <input
        type="hidden"
        name="id"
        value="<?= htmlspecialchars($equipo->getId(), ENT_QUOTES, 'UTF-8') ?>"
    >

    <button class="btn btn-danger" type="submit">
        Sí, eliminar equipo
    </button>

    <a class="btn" href="?route=equipos.index">
        Cancelar
    </a>
</form>

<?php require __DIR__ . '/../layouts/footer.php'; ?>