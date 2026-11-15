<?php

declare(strict_types=1);

require_once __DIR__ . '/../../Services/Dto/Queries/GetAllEquiposFutbolQuery.php';
require_once __DIR__ . '/../../../Domain/Models/EquipoFutbolModel.php';

interface GetAllEquiposFutbolUseCase
{
    /**
     * @return EquipoFutbolModel[]
     */
    public function execute(GetAllEquiposFutbolQuery $query): array;
}