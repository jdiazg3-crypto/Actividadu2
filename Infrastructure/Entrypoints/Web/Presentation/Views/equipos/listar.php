<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/menu.php'; ?>
<?php require __DIR__ . '/mensajes.php'; ?>

<h1>Lista de equipos de fútbol</h1>

<p>
    <a class="btn btn-primary" href="?route=equipos.create">
        Agregar equipo
    </a>

    <a class="btn" href="?route=equipos.search">
        Buscar equipos
    </a>
</p>

<?php require __DIR__ . '/tabla.php'; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>