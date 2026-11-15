<?php require __DIR__ . '/../layouts/header.php'; ?>
<?php require __DIR__ . '/../layouts/menu.php'; ?>
<?php require __DIR__ . '/mensajes.php'; ?>

<h1>Buscar equipos</h1>

<p>Busca por nombre, técnico, país, ciudad o categoría.</p>

<form method="GET" action="">
    <input type="hidden" name="route" value="equipos.search">

    <label for="search">Texto a buscar</label>

    <input
        id="search"
        name="search"
        type="search"
        value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>"
    >

    <button class="btn btn-primary" type="submit">
        Buscar
    </button>

    <a class="btn" href="?route=equipos.index">
        Ver todos
    </a>
</form>

<p><?= count($equipos) ?> equipo(s) encontrado(s).</p>

<?php require __DIR__ . '/tabla.php'; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>