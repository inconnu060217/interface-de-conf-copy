<?php

namespace App\Services\mainMenus;
use App\Services\logErrors\ILogErrorService;
use App\Services\environmentParameters\IEnvironmentParameterService;
use Illuminate\Http\JsonResponse;

class MainMenuService implements IMainMenuService
{
    private ILogErrorService $iLogErrorService;

    private  IMainMenuRepository $IMainMenuRepository;

    private IEnvironmentParameterService $IEnvironmentParameterService;

    public function __construct(ILogErrorService $iLogErrorService, IMainMenuRepository $iMenuPrincipalRepository, IEnvironmentParameterService $IEnvironmentParameterService){

        $this->iLogErrorService = $iLogErrorService;

        $this->IMainMenuRepository = $iMenuPrincipalRepository;

        $this->IEnvironmentParameterService = $IEnvironmentParameterService;
    }
    public function getAll (): JsonResponse
    {
        try {

            $methode = 'Fichier: MainMenuService - Mehtode: getAll -';

            $mainMenus = $this->IMainMenuRepository->getAll();

            $parametrageMenus = $this->IEnvironmentParameterService->getAllParameterMainMenu();

            $this->iLogErrorService->ecrireTrace(json_encode($mainMenus), 'try', $methode, 0);

            return response()->json([
                'menu' => $mainMenus,
                'parameter' => $parametrageMenus,
            ]);
        } catch (\Throwable $th) {

            $catch = 'Fichier: MainMenuService - Mehtode: getAll - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json($th->getMessage());

        }
    }
}
