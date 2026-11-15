<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../Domain/Models/EquipoFutbolModel.php';

interface SaveEquipoFutbolPort
{
    public function save(EquipoFutbolModel $equipo): EquipoFutbolModel;
}