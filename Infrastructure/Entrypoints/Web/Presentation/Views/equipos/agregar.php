<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/menu.php'; ?>
<?php require __DIR__ . '/mensajes.php'; ?>

<h1>Agregar equipo</h1>

<form method="POST" action="?route=equipos.store">
    <?php require __DIR__ . '/formulario.php'; ?>
</form>

<?php require __DIR__ . '/../layouts/footer.php'; ?>