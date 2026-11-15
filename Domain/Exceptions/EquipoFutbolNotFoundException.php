<?php

declare(strict_types=1);

final class EquipoFutbolNotFoundException extends DomainException
{
    public static function becauseIdWasNotFound(string $id): self
    {
        return new self('El equipo con id ' . $id . ' no fue encontrado.');
    }
}