<?php

namespace App\Services\call\diffusionsTypes;

use App\Services\logErrors\ILogErrorService;

class DiffusionTypeService implements IDiffusionTypeService
{
    private ILogErrorService $iLogErrorService;

    private IDiffusionTypeRepository $IDiffusionTypeRepository;

    public function __construct(ILogErrorService $iLogErrorService, IDiffusionTypeRepository $IDiffusionTypeRepository)

    {
        $this->iLogErrorService = $iLogErrorService;

        $this->IDiffusionTypeRepository = $IDiffusionTypeRepository;
        
    }
    public function getAll(){
        try {

            $methode = 'Fichier: DiffusionTypeService - Methode: getAll -';

            $diffusions = $this->IDiffusionTypeRepository->getAll();

            $this->iLogErrorService->ecrireTrace(json_encode($diffusions), 'try', $methode, 0);

            return $diffusions;

        } catch (\Throwable $th) {

            $catch = 'Fichier: DiffusionTypeService - Mehtode: getAll - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json($th->getMessage());
        }
    }

}
