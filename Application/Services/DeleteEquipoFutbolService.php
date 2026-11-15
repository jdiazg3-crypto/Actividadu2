<?php

declare(strict_types=1);

require_once __DIR__ . '/../Ports/In/DeleteEquipoFutbolUseCase.php';
require_once __DIR__ . '/../Ports/Out/GetEquipoFutbolByIdPort.php';
require_once __DIR__ . '/../Ports/Out/DeleteEquipoFutbolPort.php';
require_once __DIR__ . '/Mappers/EquipoFutbolApplicationMapper.php';
require_once __DIR__ . '/../../Domain/Exceptions/EquipoFutbolNotFoundException.php';

final class DeleteEquipoFutbolService implements DeleteEquipoFutbolUseCase
{
    private GetEquipoFutbolByIdPort $getEquipoFutbolByIdPort;
    private DeleteEquipoFutbolPort $deleteEquipoFutbolPort;

    public function __construct(
        GetEquipoFutbolByIdPort $getEquipoFutbolByIdPort,
        DeleteEquipoFutbolPort $deleteEquipoFutbolPort
    ) {
        $this->getEquipoFutbolByIdPort = $getEquipoFutbolByIdPort;
        $this->deleteEquipoFutbolPort = $deleteEquipoFutbolPort;
    }

    public function execute(DeleteEquipoFutbolCommand $command): void
    {
        $equipoId = EquipoFutbolApplicationMapper::fromDeleteCommandToEquipoId($command);

        $equipo = $this->getEquipoFutbolByIdPort->getById($equipoId);

        if ($equipo === null) {
            throw EquipoFutbolNotFoundException::becauseIdWasNotFound($equipoId->value());
        }

        $this->deleteEquipoFutbolPort->delete($equipoId);
    }
}