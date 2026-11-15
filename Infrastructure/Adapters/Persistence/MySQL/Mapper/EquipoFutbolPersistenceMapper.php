<?php

declare(strict_types=1);

require_once __DIR__ . '/../Dto/EquipoFutbolPersistenceDto.php';
require_once __DIR__ . '/../Entity/EquipoFutbolEntity.php';

require_once __DIR__ . '/../../../../../Domain/Models/EquipoFutbolModel.php';
require_once __DIR__ . '/../../../../../Domain/ValueObjects/EquipoFutbolId.php';
require_once __DIR__ . '/../../../../../Domain/ValueObjects/EquipoFutbolNombre.php';

final class EquipoFutbolPersistenceMapper
{
    public function fromModelToDto(EquipoFutbolModel $equipo): EquipoFutbolPersistenceDto
    {
        return new EquipoFutbolPersistenceDto(
            $equipo->id()->value(),
            $equipo->nombre()->value(),
            $equipo->eslogan(),
            $equipo->tecnico(),
            $equipo->pais(),
            $equipo->ciudad(),
            $equipo->categoria(),
            $equipo->numGoles(),
            $equipo->numPartidosJugados(),
            $equipo->numPartidosGanados(),
            $equipo->numEmpates(),
            $equipo->numCampeonatos(),
            $equipo->numExpulsiones()
        );
    }

    public function fromDtoToEntity(EquipoFutbolPersistenceDto $dto): EquipoFutbolEntity
    {
        return new EquipoFutbolEntity(
            $dto->id(),
            $dto->nombre(),
            $dto->eslogan(),
            $dto->tecnico(),
            $dto->pais(),
            $dto->ciudad(),
            $dto->categoria(),
            $dto->numGoles(),
            $dto->numPartidosJugados(),
            $dto->numPartidosGanados(),
            $dto->numEmpates(),
            $dto->numCampeonatos(),
            $dto->numExpulsiones()
        );
    }

    public function fromRowToEntity(array $row): EquipoFutbolEntity
    {
        return new EquipoFutbolEntity(
            (string) $row['id'],
            (string) $row['nombre'],
            isset($row['eslogan']) ? (string) $row['eslogan'] : null,
            (string) $row['tecnico'],
            (string) $row['pais'],
            (string) $row['ciudad'],
            (string) $row['categoria'],
            (int) $row['num_goles'],
            (int) $row['num_partidos_jugados'],
            (int) $row['num_partidos_ganados'],
            (int) $row['num_empates'],
            (int) $row['num_campeonatos'],
            (int) $row['num_expulsiones'],
            isset($row['created_at']) ? (string) $row['created_at'] : null,
            isset($row['updated_at']) ? (string) $row['updated_at'] : null
        );
    }

    public function fromEntityToModel(EquipoFutbolEntity $entity): EquipoFutbolModel
    {
        return new EquipoFutbolModel(
            new EquipoFutbolId($entity->id()),
            new EquipoFutbolNombre($entity->nombre()),
            $entity->eslogan(),
            $entity->tecnico(),
            $entity->pais(),
            $entity->ciudad(),
            $entity->categoria(),
            $entity->numGoles(),
            $entity->numPartidosJugados(),
            $entity->numPartidosGanados(),
            $entity->numEmpates(),
            $entity->numCampeonatos(),
            $entity->numExpulsiones()
        );
    }

    public function fromRowToModel(array $row): EquipoFutbolModel
    {
        return $this->fromEntityToModel(
            $this->fromRowToEntity($row)
        );
    }

    /**
     * @param array<int, array<string, mixed>> $rows
     * @return EquipoFutbolModel[]
     */
    public function fromRowsToModels(array $rows): array
    {
        $models = array();

        foreach ($rows as $row) {
            $models[] = $this->fromRowToModel($row);
        }

        return $models;
    }
}