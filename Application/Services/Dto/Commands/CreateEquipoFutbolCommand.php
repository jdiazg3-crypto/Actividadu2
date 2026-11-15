<?php

declare(strict_types=1);

final class CreateEquipoFutbolCommand
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
        int $numExpulsiones
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
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getEslogan(): ?string
    {
        return $this->eslogan;
    }

    public function getTecnico(): string
    {
        return $this->tecnico;
    }

    public function getPais(): string
    {
        return $this->pais;
    }

    public function getCiudad(): string
    {
        return $this->ciudad;
    }

    public function getCategoria(): string
    {
        return $this->categoria;
    }

    public function getNumGoles(): int
    {
        return $this->numGoles;
    }

    public function getNumPartidosJugados(): int
    {
        return $this->numPartidosJugados;
    }

    public function getNumPartidosGanados(): int
    {
        return $this->numPartidosGanados;
    }

    public function getNumEmpates(): int
    {
        return $this->numEmpates;
    }

    public function getNumCampeonatos(): int
    {
        return $this->numCampeonatos;
    }

    public function getNumExpulsiones(): int
    {
        return $this->numExpulsiones;
    }
}