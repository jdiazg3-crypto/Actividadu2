<?php

declare(strict_types=1);

require_once __DIR__ . '/../Exceptions/InvalidEquipoFutbolNombreException.php';

final class EquipoFutbolNombre
{
    private const MIN_LENGTH = 2;
    private const MAX_LENGTH = 100;

    private string $value;

    public function __construct(string $value)
    {
        $normalizedValue = trim($value);

        if ($normalizedValue === '') {
            throw InvalidEquipoFutbolNombreException::becauseValueIsEmpty();
        }

        if (mb_strlen($normalizedValue) < self::MIN_LENGTH) {
            throw InvalidEquipoFutbolNombreException::becauseLengthIsTooShort(self::MIN_LENGTH);
        }

        if (mb_strlen($normalizedValue) > self::MAX_LENGTH) {
            throw InvalidEquipoFutbolNombreException::becauseLengthIsTooLong(self::MAX_LENGTH);
        }

        $this->value = $normalizedValue;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(EquipoFutbolNombre $other): bool
    {
        return $this->value === $other->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}