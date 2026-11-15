<?php

declare(strict_types=1);

require_once __DIR__ . '/../Dto/Commands/CreateEquipoFutbolCommand.php';
require_once __DIR__ . '/../Dto/Commands/UpdateEquipoFutbolCommand.php';
require_once __DIR__ . '/../Dto/Commands/DeleteEquipoFutbolCommand.php';
require_once __DIR__ . '/../Dto/Queries/GetEquipoFutbolByIdQuery.php';
require_once __DIR__ . '/../../../Domain/Models/EquipoFutbolModel.php';
require_once __DIR__ . '/../../../Domain/ValueObjects/EquipoFutbolId.php';
require_once __DIR__ . '/../../../Domain/ValueObjects/EquipoFutbolNombre.php';

final class EquipoFutbolApplicationMapper
{
    public static function fromCreateCommandToModel(CreateEquipoFutbolCommand $command): EquipoFutbolModel
    {
        return new EquipoFutbolModel(
            new EquipoFutbolId($command->getId()),
            new EquipoFutbolNombre($command->getNombre()),
            $command->getEslogan(),
            $command->getTecnico(),
            $command->getPais(),
            $command->getCiudad(),
            $command->getCategoria(),
            $command->getNumGoles(),
            $command->getNumPartidosJugados(),
            $command->getNumPartidosGanados(),
            $command->getNumEmpates(),
            $command->getNumCampeonatos(),
            $command->getNumExpulsiones()
        );
    }

    public static function fromUpdateCommandToModel(UpdateEquipoFutbolCommand $command): EquipoFutbolModel
    {
        return new EquipoFutbolModel(
            new EquipoFutbolId($command->getId()),
            new EquipoFutbolNombre($command->getNombre()),
            $command->getEslogan(),
            $command->getTecnico(),
            $command->getPais(),
            $command->getCiudad(),
            $command->getCategoria(),
            $command->getNumGoles(),
            $command->getNumPartidosJugados(),
            $command->getNumPartidosGanados(),
            $command->getNumEmpates(),
            $command->getNumCampeonatos(),
            $command->getNumExpulsiones()
        );
    }

    public static function fromGetByIdQueryToEquipoId(GetEquipoFutbolByIdQuery $query): EquipoFutbolId
    {
        return new EquipoFutbolId($query->getId());
    }

    public static function fromDeleteCommandToEquipoId(DeleteEquipoFutbolCommand $command): EquipoFutbolId
    {
        return new EquipoFutbolId($command->getId());
    }

    public static function fromNombreToValueObject(string $nombre): EquipoFutbolNombre
    {
        return new EquipoFutbolNombre($nombre);
    }

    public static function fromModelToArray(EquipoFutbolModel $equipo): array
    {
        return $equipo->toArray();
    }

    /**
     * @param EquipoFutbolModel[] $equipos
     * @return array<int, array<string, mixed>>
     */
    public static function fromModelsToArray(array $equipos): array
    {
        $result = array();

        foreach ($equipos as $equipo) {
            $result[] = self::fromModelToArray($equipo);
        }

        return $result;
    }
}