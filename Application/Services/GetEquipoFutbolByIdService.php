<?php

declare(strict_types=1);

require_once __DIR__ . '/../Ports/In/GetEquipoFutbolByIdUseCase.php';
require_once __DIR__ . '/../Ports/Out/GetEquipoFutbolByIdPort.php';
require_once __DIR__ . '/Mappers/EquipoFutbolApplicationMapper.php';
require_once __DIR__ . '/../../Domain/Exceptions/EquipoFutbolNotFoundException.php';

final class GetEquipoFutbolByIdService implements GetEquipoFutbolByIdUseCase
{
    private GetEquipoFutbolByIdPort $getEquipoFutbolByIdPort;

    public function __construct(GetEquipoFutbolByIdPort $getEquipoFutbolByIdPort)
    {
        $this->getEquipoFutbolByIdPort = $getEquipoFutbolByIdPort;
    }

    public function execute(GetEquipoFutbolByIdQuery $query): EquipoFutbolModel
    {
        $equipoId = EquipoFutbolApplicationMapper::fromGetByIdQueryToEquipoId($query);

        $equipo = $this->getEquipoFutbolByIdPort->getById($equipoId);

        if ($equipo === null) {
            throw EquipoFutbolNotFoundException::becauseIdWasNotFound($equipoId->value());
        }

        return $equipo;
    }
}