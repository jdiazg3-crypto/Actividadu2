<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../Domain/Models/EquipoFutbolModel.php';

interface GetAllEquiposFutbolPort
{
    /**
     * @return EquipoFutbolModel[]
     */
    public function getAll(): array;
}