<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../Domain/Models/EquipoFutbolModel.php';
require_once __DIR__ . '/../../../Domain/ValueObjects/EquipoFutbolNombre.php';

interface GetEquipoFutbolByNombrePort
{
    public function getByNombre(EquipoFutbolNombre $nombre): ?EquipoFutbolModel;
}