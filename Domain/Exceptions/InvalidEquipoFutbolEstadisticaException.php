<?php

declare(strict_types=1);

final class InvalidEquipoFutbolEstadisticaException extends InvalidArgumentException
{
    public static function becauseValueIsNegative(string $field, int $value): self
    {
        return new self('El campo ' . $field . ' no puede ser negativo: ' . $value . '.');
    }

    public static function becauseWinsAndDrawsExceedPlayed(int $won, int $drawn, int $played): self
    {
        return new self(
            'Los partidos ganados (' . $won . ') más los empates (' . $drawn .
            ') no pueden superar los partidos jugados (' . $played . ').'
        );
    }
}