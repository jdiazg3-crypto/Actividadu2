<?php

declare(strict_types=1);

final class EquipoFutbolResponse
{
    public function __construct(
        private string $id,
        private string $nombre,
        private ?string $eslogan,
        private string $tecnico,
        private string $pais,
        private string $ciudad,
        private string $categoria,
        private int $numGoles,
        private int $numPartidosJugados,
        private int $numPartidosGanados,
        private int $numEmpates,
        private int $numCampeonatos,
        private int $numExpulsiones
    ) {
    }

    public function getId(): string { return $this->id; }
    public function getNombre(): string { return $this->nombre; }
    public function getEslogan(): ?string { return $this->eslogan; }
    public function getTecnico(): string { return $this->tecnico; }
    public function getPais(): string { return $this->pais; }
    public function getCiudad(): string { return $this->ciudad; }
    public function getCategoria(): string { return $this->categoria; }
    public function getNumGoles(): int { return $this->numGoles; }
    public function getNumPartidosJugados(): int { return $this->numPartidosJugados; }
    public function getNumPartidosGanados(): int { return $this->numPartidosGanados; }
    public function getNumEmpates(): int { return $this->numEmpates; }
    public function getNumCampeonatos(): int { return $this->numCampeonatos; }
    public function getNumExpulsiones(): int { return $this->numExpulsiones; }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}