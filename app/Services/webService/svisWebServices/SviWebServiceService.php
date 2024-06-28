<?php

namespace App\Services\webService\svisWebServices;
use App\Services\logErrors\ILogErrorService;
use App\Services\requestsCurls\IRequestCurlService;

class SviWebServiceService implements ISviWebServiceService
{
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
    public function getAll ($kidGroupe) {
        try {
            $methode = 'Fichier: SviWebServiceService - method: getAll -';

            $endPoint = "/API/Services/get?type=" . "SVIIN" . "&group=" . $kidGroupe;

            $methodHttp = "POST";

            $response = $this->iRequestCurlService->requestCurlGeneralNotCookie($endPoint, $methodHttp, null, null);

            $decodeData = json_decode($response);

            $data = json_decode($decodeData->data);

            $this->iLogErrorService->ecrireTrace(json_encode($data), 'try', $methode, 0);

            return $data ;

        } catch (\Throwable $th) {

            $catch = 'Fichier: SviWebServiceService - Mehtode: getAll - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json( $th->getMessage());
        }
    }
}
