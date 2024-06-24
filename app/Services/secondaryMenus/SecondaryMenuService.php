<?php

namespace App\Services\secondaryMenus;
use App\Services\logErrors\ILogErrorService;
use Illuminate\Http\JsonResponse;

class SecondaryMenuService implements ISecondaryMenuService
{
    private ILogErrorService $iLogErrorService;

    private ISecondaryMenuRepository $ISecondaryMenuRepository;
    
    public function __construct(ILogErrorService $iLogErrorService, ISecondaryMenuRepository $ISecondaryMenuRepository ){

        $this->iLogErrorService = $iLogErrorService;

        $this->ISecondaryMenuRepository = $ISecondaryMenuRepository;
    }
    public function getAll ($media): JsonResponse
    {
        try {
            $methode = 'Fichier: SecondaryMenuService - Mehtode: getAll -';

            $response = $this->ISecondaryMenuRepository->getAll($media);

            $this->iLogErrorService->ecrireTrace(json_encode($response), 'try', $methode, 0);

            return response()->json($response);

        } catch (\Throwable $th) {

            $catch = 'Fichier: SecondaryMenuService - Mehtode: getAll - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json($th->getMessage());
        }
    }
}
