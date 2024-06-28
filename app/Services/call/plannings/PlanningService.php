<?php

namespace App\Services\call\plannings;

use App\Services\logErrors\ILogErrorService;

use App\Services\requestsCurls\IRequestCurlService;

use App\Services\webService\planningWebService\IPlanningWebServiceService;

use Illuminate\Http\JsonResponse;

class PlanningService implements IPlanningService
{
    private IRequestCurlService $iRequestCurlService;

    private ILogErrorService $iLogErrorService;

    private IPlanningRepository $iPlanningRepository;

    private IPlanningWebServiceService $iPlanningWebServiceService;

    public function __construct(

        IRequestCurlService $iRequestCurlService,

        ILogErrorService $iLogErrorService,

        IPlanningRepository $iPlanningRepository,

        IPlanningWebServiceService $iPlanningWebServiceService

        )
    {
        $this->iRequestCurlService = $iRequestCurlService;

        $this->iLogErrorService = $iLogErrorService;

        $this->iPlanningRepository = $iPlanningRepository;

        $this->iPlanningWebServiceService = $iPlanningWebServiceService;

    }

    private function processItemArray($data, $planningBBD, $kidGroupe)

    {
        $item = [];

        if (is_array($data) && !empty($data)) {

            foreach ($data as $i) {

                if (intval($i->groupid) === intval($kidGroupe)) {

                    $item[] = $i;

                }

            }

        }

        if (is_array($item) && !empty($item) && is_array($planningBBD) && !empty($planningBBD)) {

            foreach ($item as $item1) {

                foreach ($planningBBD as &$item2) {

                    if (intval($item2["kid_planning"]) == intval($item1->id)) {

                        $item2["nom_planning"] = $item1->name;

                    }

                }

            }

        }
        return $planningBBD;
    }

    public function getAll($id, $idGroupe, $kidGroupe): JsonResponse
    {
        try {

            $methode = 'Fichier: PlanningService - Mehtode: getPlannings -';


            $data = $this->iPlanningWebServiceService->getAll();

            $planningBBD = $this->iPlanningRepository->getAll($idGroupe, $id);

            $result = $this->processItemArray($data, $planningBBD, $kidGroupe);

            $this->iLogErrorService->ecrireTrace(json_encode($result), 'try', $methode, 0);

            return response()->json($result);

        } catch (\Throwable $th) {
            $catch = 'Fichier: PlanningService - Mehtode: getPlannings - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json($th->getMessage());
        }
    }

    public function getForBankMessage($idGroupe): array {
        try {
            $methode = 'Fichier: PlanningService - Mehtode: getForBanqueMessage -';

            $data = $this->iPlanningWebServiceService->getAll();


            $response = $this->iPlanningRepository->getForBankMessage();

            $dataFinal = $this->processItemArray($data, $response, $idGroupe);

            $this->iLogErrorService->ecrireTrace(json_encode($dataFinal), 'try', $methode, 0);

            return $dataFinal;

        } catch (\Throwable $th) {

            $catch = 'Fichier: PlanningService - Mehtode: getForBankMessage - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return [$th->getMessage()];
        }
    }

    public function getAllWebService($kidGroupe): array
    {
        try {

            $methode = 'Fichier: PlanningService - Mehtode: getAllWenService -';


            $data = $this->iPlanningWebServiceService->getAll();

            $specificProperties = [];

            $item = [];

            if (is_array($data) && !empty($data)) {

                foreach ($data as $i) {

                    if (intval($i->groupid) === intval($kidGroupe)) {

                        $item[] = $i;

                    }

                }

            }

            foreach ($item as $planningWebService) {

                $serviceObject = new \stdClass();

                if (isset($planningWebService->id)) {

                    $serviceObject->id = $planningWebService->id;

                }

                if (isset($planningWebService->name)) {

                    $serviceObject->name = $planningWebService->name;

                }

                if (!empty(get_object_vars($serviceObject))) {

                    $specificProperties[] = $serviceObject;

                }
            }


            $this->iLogErrorService->ecrireTrace(json_encode($specificProperties), 'try', $methode, 0);

            return $specificProperties;

        } catch (\Throwable $th) {

            $catch = 'Fichier: PlanningService - Mehtode: getAllWenService - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return [$th->getMessage()];
        }
    }
    public function update($dataReceived){
        try {
            $methode = 'Fichier: PlanningService - Methode: updatePlanning -';

            $this->iPlanningRepository->update($dataReceived);

            $this->iLogErrorService->ecrireTrace(json_encode($dataReceived), 'try', $methode, 0);

            return response()->json(['message' => 'Votre planning a bien mis à jour.']);

        } catch (\Throwable $th) {$catch = 'Fichier: PlanningService - Mehtode: updatePlanning - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json( $th->getMessage());

        }
    }
}
