<?php

namespace App\Services\webService\planningWebService;

use App\Services\logErrors\ILogErrorService;
use App\Services\requestsCurls\IRequestCurlService;

class PlanningWebServiceService implements IPlanningWebServiceService {
    private ILogErrorService $iLogErrorService;
    private IRequestCurlService $iRequestCurlService;
    public function __construct(
        ILogErrorService $iLogErrorService,

        IRequestCurlService $iRequestCurlService,

    )
    {
        $this->iLogErrorService = $iLogErrorService;

        $this->iRequestCurlService = $iRequestCurlService;

    }
    public function getAll () {
        try {

            $methode = 'Fichier: PlanningWebServiceServiceService - Mehtode: getAll -';

            $endPoint = "/API/Plannings/all";

            $method = "POST";

            $responsePlanningWebService = $this->iRequestCurlService->requestCurlGeneralNotCookie($endPoint, $method);

            $responseArray = json_decode($responsePlanningWebService);

            $data = json_decode($responseArray->data);


            $this->iLogErrorService->ecrireTrace(json_encode($data), 'try', $methode, 0);

            return $data;

        } catch (\Throwable $th) {
            $catch = 'Fichier: PlanningWebServiceServiceService - Mehtode: getAll - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json($th->getMessage());
        }
    }
}
