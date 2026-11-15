<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../Domain/ValueObjects/EquipoFutbolId.php';

interface DeleteEquipoFutbolPort
{
    public function delete(EquipoFutbolId $equipoId): void;
}