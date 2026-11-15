<?php

declare(strict_types=1);

require_once __DIR__ . '/../../Services/Dto/Commands/DeleteEquipoFutbolCommand.php';

interface DeleteEquipoFutbolUseCase
{
    public function execute(DeleteEquipoFutbolCommand $command): void;
}