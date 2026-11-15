<?php

declare(strict_types=1);

final class UpdateEquipoFutbolWebRequest
{
    private string $id;
    private string $nombre;
    private string $eslogan;
    private string $tecnico;
    private string $pais;
    private string $ciudad;
    private string $categoria;
    private string $numGoles;
    private string $numPartidosJugados;
    private string $numPartidosGanados;
    private string $numEmpates;
    private string $numCampeonatos;
    private string $numExpulsiones;

    public function __construct(
        string $id,
        string $nombre,
        string $eslogan,
        string $tecnico,
        string $pais,
        string $ciudad,
        string $categoria,
        string $numGoles,
        string $numPartidosJugados,
        string $numPartidosGanados,
        string $numEmpates,
        string $numCampeonatos,
        string $numExpulsiones
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->eslogan = $eslogan;
        $this->tecnico = $tecnico;
        $this->pais = $pais;
        $this->ciudad = $ciudad;
        $this->categoria = $categoria;
        $this->numGoles = $numGoles;
        $this->numPartidosJugados = $numPartidosJugados;
        $this->numPartidosGanados = $numPartidosGanados;
        $this->numEmpates = $numEmpates;
        $this->numCampeonatos = $numCampeonatos;
        $this->numExpulsiones = $numExpulsiones;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function nombre(): string
    {
        return $this->nombre;
    }

    public function eslogan(): string
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

    public function numGoles(): string
    {
        return $this->numGoles;
    }

    public function numPartidosJugados(): string
    {
        return $this->numPartidosJugados;
    }

    public function numPartidosGanados(): string
    {
        return $this->numPartidosGanados;
    }

    public function numEmpates(): string
    {
        return $this->numEmpates;
    }

    public function numCampeonatos(): string
    {
        return $this->numCampeonatos;
    }

    public function numExpulsiones(): string
    {
        return $this->numExpulsiones;
    }
}