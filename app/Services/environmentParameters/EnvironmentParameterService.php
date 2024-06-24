<?php

namespace App\Services\environmentParameters;
use App\Services\logErrors\ILogErrorService;
use Illuminate\Http\JsonResponse;

class EnvironmentParameterService implements IEnvironmentParameterService
{
    private ILogErrorService $ILogErrorService;

    private IEnvironmentParameterRepository $IEnvironmentParameterRepository;

    public function __construct(ILogErrorService $ILogErrorService, IEnvironmentParameterRepository $IEnvironmentParameterRepository) {

        $this->ILogErrorService = $ILogErrorService;

        $this->IEnvironmentParameterRepository = $IEnvironmentParameterRepository;

    }
    public function getAllParameterWebService()
    {
        try {

            $methode = 'Fichier: EnvironmentParameterService - Mehtode: getParametrageEnvironnementWebService -';

            $response = $this->IEnvironmentParameterRepository->getAllParameterWebService();

            $this->ILogErrorService->ecrireTrace(json_encode($response), 'try', $methode, 0);

            return $response;

        } catch (\Throwable $th) {

            $catch = 'Fichier: EnvironmentParameterService - Mehtode: getParametrageEnvironnementWebService - catch Exception $th: -';

            $this->ILogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json($th->getMessage());
        }
    }
    public function getAllParameterTTS()
    {
        try {

            $methode = 'Fichier: EnvironmentParameterService - Mehtode: getAllParameterTTS -';

            $response = $this->IEnvironmentParameterRepository->getAllParameterTTS();

            $this->ILogErrorService->ecrireTrace(json_encode($response), 'try', $methode, 0);
            return $response;

        } catch (\Throwable $th) {

            $catch = 'Fichier: EnvironmentParameterService - Mehtode: getAllParameterTTS - catch Exception $th: -';

            $this->ILogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json($th->getMessage());

        }
    }
    public function getAllParameterMainMenu()
    {
        try {

            $methode = 'Fichier: EnvironmentParameterService - Mehtode: getAllParameterMainMenu -';

            $response = $this->IEnvironmentParameterRepository->getAllParameterMainMenu();

            $this->ILogErrorService->ecrireTrace(json_encode($response), 'try', $methode, 0);

            return $response;

        } catch (\Throwable $th) {

            $catch = 'Fichier: ParametrageEnvironnementService - Mehtode: getAllParameterMainMenu - catch Exception $th: -';

            $this->ILogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json($th->getMessage());
        }
    }
}
