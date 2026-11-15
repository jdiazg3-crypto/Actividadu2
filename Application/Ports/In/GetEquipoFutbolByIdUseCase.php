<?php

declare(strict_types=1);

require_once __DIR__ . '/../../Services/Dto/Queries/GetEquipoFutbolByIdQuery.php';
require_once __DIR__ . '/../../../Domain/Models/EquipoFutbolModel.php';

interface GetEquipoFutbolByIdUseCase
{
    public function execute(GetEquipoFutbolByIdQuery $query): EquipoFutbolModel;
}