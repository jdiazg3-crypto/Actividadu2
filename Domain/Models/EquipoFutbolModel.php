<?php

declare(strict_types=1);

require_once __DIR__ . '/../ValueObjects/EquipoFutbolId.php';
require_once __DIR__ . '/../ValueObjects/EquipoFutbolNombre.php';
require_once __DIR__ . '/../Exceptions/InvalidEquipoFutbolDatoException.php';
require_once __DIR__ . '/../Exceptions/InvalidEquipoFutbolEstadisticaException.php';

final class EquipoFutbolModel
{
    private EquipoFutbolId $id;
    private EquipoFutbolNombre $nombre;
    private ?string $eslogan;
    private string $tecnico;
    private string $pais;
    private string $ciudad;
    private string $categoria;
    private int $numGoles;
    private int $numPartidosJugados;
    private int $numPartidosGanados;
    private int $numEmpates;
    private int $numCampeonatos;
    private int $numExpulsiones;

    public function __construct(
        EquipoFutbolId $id,
        EquipoFutbolNombre $nombre,
        ?string $eslogan,
        string $tecnico,
        string $pais,
        string $ciudad,
        string $categoria,
        int $numGoles,
        int $numPartidosJugados,
        int $numPartidosGanados,
        int $numEmpates,
        int $numCampeonatos,
        int $numExpulsiones
    ) {
        self::ensureNotNegative('numGoles', $numGoles);
        self::ensureNotNegative('numPartidosJugados', $numPartidosJugados);
        self::ensureNotNegative('numPartidosGanados', $numPartidosGanados);
        self::ensureNotNegative('numEmpates', $numEmpates);
        self::ensureNotNegative('numCampeonatos', $numCampeonatos);
        self::ensureNotNegative('numExpulsiones', $numExpulsiones);

        if ($numPartidosGanados + $numEmpates > $numPartidosJugados) {
            throw InvalidEquipoFutbolEstadisticaException::becauseWinsAndDrawsExceedPlayed(
                $numPartidosGanados,
                $numEmpates,
                $numPartidosJugados
            );
        }

        $this->id = $id;
        $this->nombre = $nombre;
        $this->eslogan = self::normalizeOptionalText('eslogan', $eslogan, 150);
        $this->tecnico = self::normalizeRequiredText('tecnico', $tecnico, 100);
        $this->pais = self::normalizeRequiredText('pais', $pais, 60);
        $this->ciudad = self::normalizeRequiredText('ciudad', $ciudad, 60);
        $this->categoria = self::normalizeRequiredText('categoria', $categoria, 50);
        $this->numGoles = $numGoles;
        $this->numPartidosJugados = $numPartidosJugados;
        $this->numPartidosGanados = $numPartidosGanados;
        $this->numEmpates = $numEmpates;
        $this->numCampeonatos = $numCampeonatos;
        $this->numExpulsiones = $numExpulsiones;
    }

    public function id(): EquipoFutbolId
    {
        return $this->id;
    }

    public function nombre(): EquipoFutbolNombre
    {
        return $this->nombre;
    }

    public function eslogan(): ?string
    {
        return $this->eslogan;
    }

    public function tecnico(): string
    {
        return $this->tecnico;
    }

    public function pais(): string
    {
        return $this->pais;
    }

    public function ciudad(): string
    {
        return $this->ciudad;
    }

    public function categoria(): string
    {
        return $this->categoria;
    }

    public function numGoles(): int
    {
        return $this->numGoles;
    }

    public function numPartidosJugados(): int
    {
        return $this->numPartidosJugados;
    }

    public function numPartidosGanados(): int
    {
        return $this->numPartidosGanados;
    }

    public function numEmpates(): int
    {
        return $this->numEmpates;
    }

    public function numCampeonatos(): int
    {
        return $this->numCampeonatos;
    }

    public function numExpulsiones(): int
    {
        return $this->numExpulsiones;
    }

    public function toArray(): array
    {
        return array(
            'id' => $this->id->value(),
            'nombre' => $this->nombre->value(),
            'eslogan' => $this->eslogan,
            'tecnico' => $this->tecnico,
            'pais' => $this->pais,
            'ciudad' => $this->ciudad,
            'categoria' => $this->categoria,
            'numGoles' => $this->numGoles,
            'numPartidosJugados' => $this->numPartidosJugados,
            'numPartidosGanados' => $this->numPartidosGanados,
            'numEmpates' => $this->numEmpates,
            'numCampeonatos' => $this->numCampeonatos,
            'numExpulsiones' => $this->numExpulsiones
        );
    }

    private static function ensureNotNegative(string $field, int $value): void
    {
        if ($value < 0) {
            throw InvalidEquipoFutbolEstadisticaException::becauseValueIsNegative($field, $value);
        }
    }

    private static function normalizeRequiredText(string $field, string $value, int $maxLength): string
    {
        $normalizedValue = trim($value);

        if ($normalizedValue === '') {
            throw InvalidEquipoFutbolDatoException::becauseValueIsEmpty($field);
        }

        if (mb_strlen($normalizedValue) > $maxLength) {
            throw InvalidEquipoFutbolDatoException::becauseLengthIsTooLong($field, $maxLength);
        }

        return $normalizedValue;
    }

    private static function normalizeOptionalText(string $field, ?string $value, int $maxLength): ?string
    {
        if ($value === null) {
            return null;
        }

        $normalizedValue = trim($value);

        if ($normalizedValue === '') {
            return null;
        }

        if (mb_strlen($normalizedValue) > $maxLength) {
            throw InvalidEquipoFutbolDatoException::becauseLengthIsTooLong($field, $maxLength);
        }

        return $normalizedValue;
    }
}