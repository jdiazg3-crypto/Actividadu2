<?php

declare(strict_types=1);

final class EquipoFutbolAlreadyExistsException extends DomainException
{
    public static function becauseNombreAlreadyExists(string $nombre): self
    {
        return new self('Ya existe un equipo con el nombre ' . $nombre . '.');
    }
}