<?php

namespace App\Services\call\entryPointTypes;

use App\Services\logErrors\ILogErrorService;
use Illuminate\Http\JsonResponse;

class EntryPointTypeService implements IEntryPointTypeService
{

    private ILogErrorService $ILogErrorService;

    private IEntryPointTypeRepository $IEntryPointTypeRepository;

    public function __construct(

        ILogErrorService $ILogErrorService,

        IEntryPointTypeRepository $IEntryPointTypeRepository
        
        )
    {
        $this->ILogErrorService = $ILogErrorService;

        $this->IEntryPointTypeRepository = $IEntryPointTypeRepository;
    }
    public function getAll(): JsonResponse
    {
        try {
            $methode = 'Fichier: EntryPointTypeService - Methode: getAll -';

            $response = $this->IEntryPointTypeRepository->getAll();

            $this->ILogErrorService->ecrireTrace(json_encode($response), 'try', $methode, 0);

            return response()->json($response);

        } catch (\Throwable $th) {

            $catch = 'Fichier: EntryPointTypeService - Mehtode: getAll - catch Exception $th: -';

            $this->ILogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json($th->getMessage());

        }
    }
}
