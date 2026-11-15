<?php

declare(strict_types=1);

require_once __DIR__ . '/Dto/CreateEquipoFutbolRequest.php';
require_once __DIR__ . '/Dto/UpdateEquipoFutbolRequest.php';
require_once __DIR__ . '/Dto/EquipoFutbolResponse.php';
require_once __DIR__ . '/Mapper/EquipoFutbolWebMapper.php';

require_once __DIR__ . '/../../../../Application/Ports/In/CreateEquipoFutbolUseCase.php';
require_once __DIR__ . '/../../../../Application/Ports/In/UpdateEquipoFutbolUseCase.php';
require_once __DIR__ . '/../../../../Application/Ports/In/GetEquipoFutbolByIdUseCase.php';
require_once __DIR__ . '/../../../../Application/Ports/In/GetAllEquiposFutbolUseCase.php';
require_once __DIR__ . '/../../../../Application/Ports/In/DeleteEquipoFutbolUseCase.php';
require_once __DIR__ . '/../../../../Application/Services/Dto/Commands/DeleteEquipoFutbolCommand.php';
require_once __DIR__ . '/../../../../Application/Services/Dto/Queries/GetEquipoFutbolByIdQuery.php';
require_once __DIR__ . '/../../../../Application/Services/Dto/Queries/GetAllEquiposFutbolQuery.php';

final class EquipoFutbolController
{
    private CreateEquipoFutbolUseCase $createEquipoFutbolUseCase;
    private UpdateEquipoFutbolUseCase $updateEquipoFutbolUseCase;
    private GetEquipoFutbolByIdUseCase $getEquipoFutbolByIdUseCase;
    private GetAllEquiposFutbolUseCase $getAllEquiposFutbolUseCase;
    private DeleteEquipoFutbolUseCase $deleteEquipoFutbolUseCase;
    private EquipoFutbolWebMapper $mapper;

    public function __construct(
        CreateEquipoFutbolUseCase $createEquipoFutbolUseCase,
        UpdateEquipoFutbolUseCase $updateEquipoFutbolUseCase,
        GetEquipoFutbolByIdUseCase $getEquipoFutbolByIdUseCase,
        GetAllEquiposFutbolUseCase $getAllEquiposFutbolUseCase,
        DeleteEquipoFutbolUseCase $deleteEquipoFutbolUseCase,
        EquipoFutbolWebMapper $mapper
    ) {
        $this->createEquipoFutbolUseCase = $createEquipoFutbolUseCase;
        $this->updateEquipoFutbolUseCase = $updateEquipoFutbolUseCase;
        $this->getEquipoFutbolByIdUseCase = $getEquipoFutbolByIdUseCase;
        $this->getAllEquiposFutbolUseCase = $getAllEquiposFutbolUseCase;
        $this->deleteEquipoFutbolUseCase = $deleteEquipoFutbolUseCase;
        $this->mapper = $mapper;
    }

    public function store(CreateEquipoFutbolWebRequest $request): EquipoFutbolResponse
    {
        $command = $this->mapper->fromCreateRequestToCommand($request);
        $equipo = $this->createEquipoFutbolUseCase->execute($command);

        return $this->mapper->fromModelToResponse($equipo);
    }

    /**
     * @return EquipoFutbolResponse[]
     */
    public function index(?string $search = null): array
    {
        $equipos = $this->getAllEquiposFutbolUseCase->execute(
            new GetAllEquiposFutbolQuery($search)
        );

        return $this->mapper->fromModelsToResponses($equipos);
    }

    public function show(string $id): EquipoFutbolResponse
    {
        $equipo = $this->getEquipoFutbolByIdUseCase->execute(
            new GetEquipoFutbolByIdQuery($id)
        );

        return $this->mapper->fromModelToResponse($equipo);
    }

    public function update(UpdateEquipoFutbolWebRequest $request): EquipoFutbolResponse
    {
        $command = $this->mapper->fromUpdateRequestToCommand($request);
        $equipo = $this->updateEquipoFutbolUseCase->execute($command);

        return $this->mapper->fromModelToResponse($equipo);
    }

    public function delete(string $id): void
    {
        $this->deleteEquipoFutbolUseCase->execute(
            new DeleteEquipoFutbolCommand($id)
        );
    }
}