<?php

declare(strict_types=1);

require_once __DIR__ . '/../../Services/Dto/Commands/CreateEquipoFutbolCommand.php';
require_once __DIR__ . '/../../../Domain/Models/EquipoFutbolModel.php';

interface CreateEquipoFutbolUseCase
{
    public function execute(CreateEquipoFutbolCommand $command): EquipoFutbolModel;
}