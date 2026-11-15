<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../../../Application/Ports/Out/SaveEquipoFutbolPort.php';
require_once __DIR__ . '/../../../../../Application/Ports/Out/UpdateEquipoFutbolPort.php';
require_once __DIR__ . '/../../../../../Application/Ports/Out/GetEquipoFutbolByIdPort.php';
require_once __DIR__ . '/../../../../../Application/Ports/Out/GetEquipoFutbolByNombrePort.php';
require_once __DIR__ . '/../../../../../Application/Ports/Out/GetAllEquiposFutbolPort.php';
require_once __DIR__ . '/../../../../../Application/Ports/Out/DeleteEquipoFutbolPort.php';

require_once __DIR__ . '/../Mapper/EquipoFutbolPersistenceMapper.php';
require_once __DIR__ . '/../../../../../Domain/Models/EquipoFutbolModel.php';
require_once __DIR__ . '/../../../../../Domain/ValueObjects/EquipoFutbolId.php';
require_once __DIR__ . '/../../../../../Domain/ValueObjects/EquipoFutbolNombre.php';

final class EquipoFutbolRepositoryMySQL implements
    SaveEquipoFutbolPort,
    UpdateEquipoFutbolPort,
    GetEquipoFutbolByIdPort,
    GetEquipoFutbolByNombrePort,
    GetAllEquiposFutbolPort,
    DeleteEquipoFutbolPort
{
    private const SELECT_COLUMNS = '
        id,
        nombre,
        eslogan,
        tecnico,
        pais,
        ciudad,
        categoria,
        num_goles,
        num_partidos_jugados,
        num_partidos_ganados,
        num_empates,
        num_campeonatos,
        num_expulsiones,
        created_at,
        updated_at
    ';

    private PDO $pdo;
    private EquipoFutbolPersistenceMapper $mapper;

    public function __construct(PDO $pdo, EquipoFutbolPersistenceMapper $mapper)
    {
        $this->pdo = $pdo;
        $this->mapper = $mapper;
    }

    public function save(EquipoFutbolModel $equipo): EquipoFutbolModel
    {
        $dto = $this->mapper->fromModelToDto($equipo);

        $sql = '
            INSERT INTO equipos_futbol (
                id,
                nombre,
                eslogan,
                tecnico,
                pais,
                ciudad,
                categoria,
                num_goles,
                num_partidos_jugados,
                num_partidos_ganados,
                num_empates,
                num_campeonatos,
                num_expulsiones,
                created_at,
                updated_at
            ) VALUES (
                :id,
                :nombre,
                :eslogan,
                :tecnico,
                :pais,
                :ciudad,
                :categoria,
                :num_goles,
                :num_partidos_jugados,
                :num_partidos_ganados,
                :num_empates,
                :num_campeonatos,
                :num_expulsiones,
                NOW(),
                NOW()
            )
        ';

        $statement = $this->pdo->prepare($sql);
        $statement->execute($this->dtoToParameters($dto));

        $savedEquipo = $this->getById(new EquipoFutbolId($dto->id()));

        if ($savedEquipo === null) {
            throw new RuntimeException('El equipo no pudo recuperarse después de guardarlo.');
        }

        return $savedEquipo;
    }

    public function update(EquipoFutbolModel $equipo): EquipoFutbolModel
    {
        $dto = $this->mapper->fromModelToDto($equipo);

        $sql = '
            UPDATE equipos_futbol
               SET nombre = :nombre,
                   eslogan = :eslogan,
                   tecnico = :tecnico,
                   pais = :pais,
                   ciudad = :ciudad,
                   categoria = :categoria,
                   num_goles = :num_goles,
                   num_partidos_jugados = :num_partidos_jugados,
                   num_partidos_ganados = :num_partidos_ganados,
                   num_empates = :num_empates,
                   num_campeonatos = :num_campeonatos,
                   num_expulsiones = :num_expulsiones,
                   updated_at = NOW()
             WHERE id = :id
        ';

        $statement = $this->pdo->prepare($sql);
        $statement->execute($this->dtoToParameters($dto));

        $updatedEquipo = $this->getById(new EquipoFutbolId($dto->id()));

        if ($updatedEquipo === null) {
            throw new RuntimeException('El equipo no pudo recuperarse después de actualizarlo.');
        }

        return $updatedEquipo;
    }

    public function getById(EquipoFutbolId $equipoId): ?EquipoFutbolModel
    {
        $sql = 'SELECT ' . self::SELECT_COLUMNS . ' FROM equipos_futbol WHERE id = :id LIMIT 1';

        $statement = $this->pdo->prepare($sql);
        $statement->execute(array(
            ':id' => $equipoId->value(),
        ));

        $row = $statement->fetch();

        if ($row === false) {
            return null;
        }

        return $this->mapper->fromRowToModel($row);
    }

    public function getByNombre(EquipoFutbolNombre $nombre): ?EquipoFutbolModel
    {
        $sql = 'SELECT ' . self::SELECT_COLUMNS . ' FROM equipos_futbol WHERE nombre = :nombre LIMIT 1';

        $statement = $this->pdo->prepare($sql);
        $statement->execute(array(
            ':nombre' => $nombre->value(),
        ));

        $row = $statement->fetch();

        if ($row === false) {
            return null;
        }

        return $this->mapper->fromRowToModel($row);
    }

    /**
     * @return EquipoFutbolModel[]
     */
    public function getAll(): array
    {
        $sql = 'SELECT ' . self::SELECT_COLUMNS . ' FROM equipos_futbol ORDER BY nombre ASC';

        $statement = $this->pdo->query($sql);
        $rows = $statement->fetchAll();

        return $this->mapper->fromRowsToModels($rows);
    }

    public function delete(EquipoFutbolId $equipoId): void
    {
        $sql = 'DELETE FROM equipos_futbol WHERE id = :id';

        $statement = $this->pdo->prepare($sql);
        $statement->execute(array(
            ':id' => $equipoId->value(),
        ));
    }

    /**
     * @return array<string, mixed>
     */
    private function dtoToParameters(EquipoFutbolPersistenceDto $dto): array
    {
        return array(
            ':id' => $dto->id(),
            ':nombre' => $dto->nombre(),
            ':eslogan' => $dto->eslogan(),
            ':tecnico' => $dto->tecnico(),
            ':pais' => $dto->pais(),
            ':ciudad' => $dto->ciudad(),
            ':categoria' => $dto->categoria(),
            ':num_goles' => $dto->numGoles(),
            ':num_partidos_jugados' => $dto->numPartidosJugados(),
            ':num_partidos_ganados' => $dto->numPartidosGanados(),
            ':num_empates' => $dto->numEmpates(),
            ':num_campeonatos' => $dto->numCampeonatos(),
            ':num_expulsiones' => $dto->numExpulsiones(),
        );
    }
}