<?php

namespace App\Services\call\queueCalls;

use App\Services\call\plannings\IPlanningService;
use App\Services\logErrors\ILogErrorService;
use App\Services\webService\queueCallWebServices\IQueueCallWebServiceService;
use Illuminate\Http\JsonResponse;

class QueueCallService implements  IQueueCallService
{
    private ILogErrorService $iLogErrorService;

    private IQueueCallWebServiceService $IQueueCallWebServiceService;

    private IQueueCallRepository $IQueueCallRepository;

    //private IPlanningService $iPlanningService;

    public function __construct(
        ILogErrorService              $iLogErrorService,

        IQueueCallWebServiceService $IQueueCallWebServiceService,

        IQueueCallRepository          $IQueueCallRepository,

        //IPlanningService              $iPlanningService

        )
    {
        $this->iLogErrorService = $iLogErrorService;

        $this->IQueueCallWebServiceService = $IQueueCallWebServiceService;

        $this->IQueueCallRepository = $IQueueCallRepository;

        //$this->iPlanningService = $iPlanningService;

    }
    public function findIdFileAttenteCallback($dataServiceNameFileAttente, $item2) {

        foreach ($dataServiceNameFileAttente as $item1) {

            if (intval($item2["kid_file_attente"]) == intval($item1->ServiceId)) {

                return $item1->ServiceName;

            }
        }

        return false;

    }

    public function findIdFileAttenteWQLevelCallback($dataServiceNameFileAttente, $item2) {

        foreach ($dataServiceNameFileAttente as $item1) {

            if (intval($item2["kid_file_attente"]) == intval($item1->ServiceId)) {

                return intval($item1->ServiceWQLevel);

            }

        }

        return false;

    }

    public function findIdFileAttenteDebordementCallback($dataServiceNameFileAttente, $item2) {

        foreach ($dataServiceNameFileAttente as $item1) {

            if (intval($item2["kid_file_attente_debordement"]) == intval($item1->ServiceId)) {

                return $item1->ServiceName;

            }

        }

        return false;

    }

    public function findIdCallbackPlanning($dataPlannings, $item2) {

        foreach ($dataPlannings as $item1) {

            if (intval($item2["kid_planning_fa"]) == intval($item1->id)) {

                return $item1->name;

            }

        }

        return false;

    }


    public function getAll ($idGroupe, $kidGroupe){
        /*try {

            $methode = 'Fichier: QueueCallService - methode: getAll -';

            $dataServiceNameFileAttente = $this->IQueueCallWebServiceService->getAll($kidGroupe);

            $dataPlannings = $this->iPlanningService->getAllWebService($kidGroupe);

            $queueCalls = $this->IQueueCallRepository->getAll($idGroupe);

            if(!empty($dataPlannings) && is_array($queueCalls) && !empty($queueCalls)) {
                foreach ($queueCalls as &$item2) {
                    $item2["nom_fa_planning"] = $this->findIdCallbackPlanning($dataPlannings, $item2);
                }
            }

            if(is_array($dataServiceNameFileAttente) && !empty($dataServiceNameFileAttente) && is_array($queueCalls) && !empty($queueCalls)) {
                foreach ($queueCalls as &$item2) {

                    $item2["temps_post_appel"] = $this->IQueueCallWebServiceService->getPostCallTime($item2["kid_file_attente"]);

                    $item2["nom_file_attente"] = $this->findIdFileAttenteCallback($dataServiceNameFileAttente, $item2);

                    $item2["File_attente_debordement"] = $this->findIdFileAttenteDebordementCallback($dataServiceNameFileAttente, $item2);

                    $item2["propriete"] = $this->findIdFileAttenteWQLevelCallback($dataServiceNameFileAttente, $item2);

                }

            }

            $this->iLogErrorService->ecrireTrace(json_encode($queueCalls), 'try', $methode, 0);

            return response()->json($queueCalls);

        } catch (\Throwable $th) {

            $catch = 'Fichier: QueueCallService - Mehtode: getAll - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json($th->getMessage());

        }*/
    }

    public function getAllQueueWebServices ($kidGroupe)
    {
        try {
            $methode = 'Fichier: QueueCallService - methode: getAllQueueWebServices -';

            $queueCallWebServices = $this->IQueueCallWebServiceService->getAll($kidGroupe);

            $specificProperties = [];

            foreach ($queueCallWebServices as $queueCallWebService) {
                $serviceObject = new \stdClass();
                if (isset($queueCallWebService->ServiceId)) {
                    $serviceObject->serviceId = $queueCallWebService->ServiceId;
                }
                if (isset($queueCallWebService->ServiceName)) {
                    $serviceObject->serviceName = $queueCallWebService->ServiceName;
                }

                if (!empty(get_object_vars($serviceObject))) {
                    $specificProperties[] = $serviceObject;
                }
            }


            $this->iLogErrorService->ecrireTrace(json_encode($specificProperties), 'try', $methode, 0);

            return response()->json($specificProperties);

        } catch (\Throwable $th) {

            $catch = 'Fichier: QueueCallService - Mehtode: getAllQueueWebServices - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json($th->getMessage());


        }
    }

    public function update($dataReceived): JsonResponse
    {
        try {
            $methode = 'Fichier: QueueCallService - Methode: update -';

            $response = $this->IQueueCallRepository->update($dataReceived);

            $this->iLogErrorService->ecrireTrace(json_encode($dataReceived), 'try', $methode, 0);

            if($response === true) {

                return response()->json(['message' => 'La file d\'attente a bien été mise à jour.']);

            }

            else {

                return response()->json(['status' => 400, 'message' => "Une erreur s'est produite lors de la requête. Veuillez ressayer."]);

            }
        } catch (\Throwable $th) {

            $catch = 'Fichier: QueueCallService - Mehtode: update - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json($th->getMessage());

        }
    }
}
