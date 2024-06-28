<?php

namespace App\Services\call\overflowTypes;
use App\Services\logErrors\ILogErrorService;

class OverflowTypeService implements IOverflowTypeService
{
    private ILogErrorService $iLogErrorService;

    private OverflowTypeRepository $OverflowTypeRepository;
    
    public function __construct(ILogErrorService $iLogErrorService, OverflowTypeRepository $OverflowTypeRepository)
    {

        $this->iLogErrorService = $iLogErrorService;

        $this->OverflowTypeRepository = $OverflowTypeRepository;

    }
    public function getAll(){
        try {

            $methode = 'Fichier: OverflowTypeService - Methode: getAll -';

            $response = $this->OverflowTypeRepository->getAll();

            $this->iLogErrorService->ecrireTrace(json_encode($response), 'try', $methode, 0);

            return $response;

        } catch (\Throwable $th) {

            $catch = 'Fichier: OverflowTypeService - Mehtode: getAll - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json( $th->getMessage());

        }
    }
}
