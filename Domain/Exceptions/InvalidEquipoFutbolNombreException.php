<?php

declare(strict_types=1);

final class InvalidEquipoFutbolNombreException extends InvalidArgumentException
{
    public static function becauseValueIsEmpty(): self
    {
        return new self('El nombre del equipo no puede estar vacío.');
    }

    public static function becauseLengthIsTooShort(int $minLength): self
    {
        return new self('El nombre del equipo debe tener al menos ' . $minLength . ' caracteres.');
    }

    public static function becauseLengthIsTooLong(int $maxLength): self
    {
        return new self('El nombre del equipo no puede superar ' . $maxLength . ' caracteres.');
    }
}