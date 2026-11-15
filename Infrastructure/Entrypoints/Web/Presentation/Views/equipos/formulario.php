<?php
$values = array_merge(
    isset($equipo) ? $equipo->toArray() : array(),
    $old ?? array()
);
?>

<input
    type="hidden"
    name="csrf"
    value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>"
>

<?php if (isset($equipo)): ?>
    <input
        type="hidden"
        name="id"
        value="<?= htmlspecialchars($equipo->getId(), ENT_QUOTES, 'UTF-8') ?>"
    >
<?php endif; ?>

<?php foreach ($fields as $key => $definition): ?>
    <div class="form-group">
        <label for="<?= $key ?>">
            <?= $definition['label'] ?>
            <?= $key === 'eslogan' ? ' (opcional)' : '' ?>
        </label>
        <br>

        <input
            id="<?= $key ?>"
            name="<?= $key ?>"
            type="<?= $definition['type'] ?>"
            value="<?= htmlspecialchars(
                (string) (
                    $values[$key]
                    ?? ($definition['type'] === 'number' ? '0' : '')
                ),
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
            <?php if ($definition['type'] === 'number'): ?>
                min="0"
                max="2147483647"
                step="1"
                required
            <?php else: ?>
                maxlength="<?= $definition['max'] ?>"
                <?= $definition['required'] ? 'required' : '' ?>
                <?= $key === 'nombre' ? 'minlength="2"' : '' ?>
            <?php endif; ?>
            <?php if (isset($errors[$key])): ?>
                aria-invalid="true"
                aria-describedby="error-<?= $key ?>"
            <?php endif; ?>
        >

        <?php if (isset($errors[$key])): ?>
            <div class="field-error" id="error-<?= $key ?>">
                <?= htmlspecialchars($errors[$key], ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

<button class="btn btn-primary" type="submit">
    Guardar equipo
</button>

<a class="btn" href="?route=equipos.index">Cancelar</a>