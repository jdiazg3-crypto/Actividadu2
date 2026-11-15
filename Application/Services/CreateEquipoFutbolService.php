<?php

declare(strict_types=1);

require_once __DIR__ . '/../Ports/In/CreateEquipoFutbolUseCase.php';
require_once __DIR__ . '/../Ports/Out/SaveEquipoFutbolPort.php';
require_once __DIR__ . '/../Ports/Out/GetEquipoFutbolByNombrePort.php';
require_once __DIR__ . '/Mappers/EquipoFutbolApplicationMapper.php';
require_once __DIR__ . '/../../Domain/Exceptions/EquipoFutbolAlreadyExistsException.php';

final class CreateEquipoFutbolService implements CreateEquipoFutbolUseCase
{
    private SaveEquipoFutbolPort $saveEquipoFutbolPort;
    private GetEquipoFutbolByNombrePort $getEquipoFutbolByNombrePort;

    public function __construct(
        SaveEquipoFutbolPort $saveEquipoFutbolPort,
        GetEquipoFutbolByNombrePort $getEquipoFutbolByNombrePort
    ) {
        $this->saveEquipoFutbolPort = $saveEquipoFutbolPort;
        $this->getEquipoFutbolByNombrePort = $getEquipoFutbolByNombrePort;
    }

    public function execute(CreateEquipoFutbolCommand $command): EquipoFutbolModel
    {
        $equipo = EquipoFutbolApplicationMapper::fromCreateCommandToModel($command);

        $existing = $this->getEquipoFutbolByNombrePort->getByNombre($equipo->nombre());

        if ($existing !== null) {
            throw EquipoFutbolAlreadyExistsException::becauseNombreAlreadyExists($equipo->nombre()->value());
        }

        return $this->saveEquipoFutbolPort->save($equipo);
    }
}