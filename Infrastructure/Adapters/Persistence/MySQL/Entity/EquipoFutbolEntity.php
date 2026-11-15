<?php

declare(strict_types=1);

final class EquipoFutbolEntity
{
    private string $id;
    private string $nombre;
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
    private ?string $createdAt;
    private ?string $updatedAt;

    public function __construct(
        string $id,
        string $nombre,
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
        int $numExpulsiones,
        ?string $createdAt = null,
        ?string $updatedAt = null
    ) {
        $this->id = trim($id);
        $this->nombre = trim($nombre);
        $this->eslogan = $eslogan !== null ? trim($eslogan) : null;
        $this->tecnico = trim($tecnico);
        $this->pais = trim($pais);
        $this->ciudad = trim($ciudad);
        $this->categoria = trim($categoria);
        $this->numGoles = $numGoles;
        $this->numPartidosJugados = $numPartidosJugados;
        $this->numPartidosGanados = $numPartidosGanados;
        $this->numEmpates = $numEmpates;
        $this->numCampeonatos = $numCampeonatos;
        $this->numExpulsiones = $numExpulsiones;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function nombre(): string
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

    public function createdAt(): ?string
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?string
    {
        return $this->updatedAt;
    }
}