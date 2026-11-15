<?php

declare(strict_types=1);

require_once __DIR__ . '/../Ports/In/UpdateEquipoFutbolUseCase.php';
require_once __DIR__ . '/../Ports/Out/UpdateEquipoFutbolPort.php';
require_once __DIR__ . '/../Ports/Out/GetEquipoFutbolByIdPort.php';
require_once __DIR__ . '/../Ports/Out/GetEquipoFutbolByNombrePort.php';
require_once __DIR__ . '/Mappers/EquipoFutbolApplicationMapper.php';
require_once __DIR__ . '/../../Domain/Exceptions/EquipoFutbolNotFoundException.php';
require_once __DIR__ . '/../../Domain/Exceptions/EquipoFutbolAlreadyExistsException.php';

final class UpdateEquipoFutbolService implements UpdateEquipoFutbolUseCase
{
    private UpdateEquipoFutbolPort $updateEquipoFutbolPort;
    private GetEquipoFutbolByIdPort $getEquipoFutbolByIdPort;
    private GetEquipoFutbolByNombrePort $getEquipoFutbolByNombrePort;

    public function __construct(
        UpdateEquipoFutbolPort $updateEquipoFutbolPort,
        GetEquipoFutbolByIdPort $getEquipoFutbolByIdPort,
        GetEquipoFutbolByNombrePort $getEquipoFutbolByNombrePort
    ) {
        $this->updateEquipoFutbolPort = $updateEquipoFutbolPort;
        $this->getEquipoFutbolByIdPort = $getEquipoFutbolByIdPort;
        $this->getEquipoFutbolByNombrePort = $getEquipoFutbolByNombrePort;
    }

    public function execute(UpdateEquipoFutbolCommand $command): EquipoFutbolModel
    {
        $equipo = EquipoFutbolApplicationMapper::fromUpdateCommandToModel($command);

        $current = $this->getEquipoFutbolByIdPort->getById($equipo->id());

        if ($current === null) {
            throw EquipoFutbolNotFoundException::becauseIdWasNotFound($equipo->id()->value());
        }

        $sameNombre = $this->getEquipoFutbolByNombrePort->getByNombre($equipo->nombre());

        if ($sameNombre !== null && !$sameNombre->id()->equals($equipo->id())) {
            throw EquipoFutbolAlreadyExistsException::becauseNombreAlreadyExists($equipo->nombre()->value());
        }

        return $this->updateEquipoFutbolPort->update($equipo);
    }
}