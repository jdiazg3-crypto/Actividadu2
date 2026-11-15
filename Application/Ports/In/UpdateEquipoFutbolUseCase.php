<?php

declare(strict_types=1);

require_once __DIR__ . '/../../Services/Dto/Commands/UpdateEquipoFutbolCommand.php';
require_once __DIR__ . '/../../../Domain/Models/EquipoFutbolModel.php';

interface UpdateEquipoFutbolUseCase
{
    public function execute(UpdateEquipoFutbolCommand $command): EquipoFutbolModel;
}