<?php

declare(strict_types=1);

final class InvalidEquipoFutbolIdException extends InvalidArgumentException
{
    public static function becauseValueIsEmpty(): self
    {
        return new self('El id del equipo no puede estar vacío.');
    }
}