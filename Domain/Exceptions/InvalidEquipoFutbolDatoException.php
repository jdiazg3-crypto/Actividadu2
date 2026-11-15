<?php

declare(strict_types=1);

final class InvalidEquipoFutbolDatoException extends InvalidArgumentException
{
    public static function becauseValueIsEmpty(string $field): self
    {
        return new self('El campo ' . $field . ' del equipo es obligatorio.');
    }

    public static function becauseLengthIsTooLong(string $field, int $maxLength): self
    {
        return new self('El campo ' . $field . ' no puede superar ' . $maxLength . ' caracteres.');
    }
}