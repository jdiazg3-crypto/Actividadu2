<?php

declare(strict_types=1);

require_once __DIR__ . '/../Ports/In/GetAllEquiposFutbolUseCase.php';
require_once __DIR__ . '/../Ports/Out/GetAllEquiposFutbolPort.php';

final class GetAllEquiposFutbolService implements GetAllEquiposFutbolUseCase
{
    private GetAllEquiposFutbolPort $getAllEquiposFutbolPort;

    public function __construct(GetAllEquiposFutbolPort $getAllEquiposFutbolPort)
    {
        $this->getAllEquiposFutbolPort = $getAllEquiposFutbolPort;
    }

    /**
     * @return EquipoFutbolModel[]
     */
    public function execute(GetAllEquiposFutbolQuery $query): array
    {
        $equipos = $this->getAllEquiposFutbolPort->getAll();

        $search = $query->getSearch();

        if ($search === null || $search === '') {
            return $equipos;
        }

        $needle = mb_strtolower($search);

        return array_values(array_filter(
            $equipos,
            static function (EquipoFutbolModel $equipo) use ($needle): bool {
                $haystack = mb_strtolower(
                    $equipo->nombre()->value() . ' ' .
                    $equipo->tecnico() . ' ' .
                    $equipo->pais() . ' ' .
                    $equipo->ciudad() . ' ' .
                    $equipo->categoria()
                );

                return mb_strpos($haystack, $needle) !== false;
            }
        ));
    }
}