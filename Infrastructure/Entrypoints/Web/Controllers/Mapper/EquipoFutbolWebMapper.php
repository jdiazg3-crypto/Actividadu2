<?php

declare(strict_types=1);

require_once __DIR__ . '/../Dto/CreateEquipoFutbolRequest.php';
require_once __DIR__ . '/../Dto/UpdateEquipoFutbolRequest.php';
require_once __DIR__ . '/../Dto/EquipoFutbolResponse.php';

require_once __DIR__ . '/../../../../../Application/Services/Dto/Commands/CreateEquipoFutbolCommand.php';
require_once __DIR__ . '/../../../../../Application/Services/Dto/Commands/UpdateEquipoFutbolCommand.php';
require_once __DIR__ . '/../../../../../Domain/Models/EquipoFutbolModel.php';

final class EquipoFutbolWebMapper
{
    public function fromCreateRequestToCommand(CreateEquipoFutbolWebRequest $request): CreateEquipoFutbolCommand
    {
        return new CreateEquipoFutbolCommand(
            $request->id(),
            $request->nombre(),
            $request->eslogan(),
            $request->tecnico(),
            $request->pais(),
            $request->ciudad(),
            $request->categoria(),
            $this->toInt('goles', $request->numGoles()),
            $this->toInt('partidos jugados', $request->numPartidosJugados()),
            $this->toInt('partidos ganados', $request->numPartidosGanados()),
            $this->toInt('empates', $request->numEmpates()),
            $this->toInt('campeonatos', $request->numCampeonatos()),
            $this->toInt('expulsiones', $request->numExpulsiones())
        );
    }

    public function fromUpdateRequestToCommand(UpdateEquipoFutbolWebRequest $request): UpdateEquipoFutbolCommand
    {
        return new UpdateEquipoFutbolCommand(
            $request->id(),
            $request->nombre(),
            $request->eslogan(),
            $request->tecnico(),
            $request->pais(),
            $request->ciudad(),
            $request->categoria(),
            $this->toInt('goles', $request->numGoles()),
            $this->toInt('partidos jugados', $request->numPartidosJugados()),
            $this->toInt('partidos ganados', $request->numPartidosGanados()),
            $this->toInt('empates', $request->numEmpates()),
            $this->toInt('campeonatos', $request->numCampeonatos()),
            $this->toInt('expulsiones', $request->numExpulsiones())
        );
    }

    public function fromModelToResponse(EquipoFutbolModel $equipo): EquipoFutbolResponse
    {
        return new EquipoFutbolResponse(
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

    /**
     * @param EquipoFutbolModel[] $equipos
     * @return EquipoFutbolResponse[]
     */
    public function fromModelsToResponses(array $equipos): array
    {
        $responses = array();

        foreach ($equipos as $equipo) {
            $responses[] = $this->fromModelToResponse($equipo);
        }

        return $responses;
    }

    private function toInt(string $field, string $value): int
    {
        $normalizedValue = trim($value);

        if ($normalizedValue === '') {
            return 0;
        }

        $number = filter_var($normalizedValue, FILTER_VALIDATE_INT);

        if ($number === false) {
            throw new InvalidArgumentException('El campo ' . $field . ' debe ser un número entero.');
        }

        return $number;
    }
}