<?php

namespace App\Services\webService\queueEmailWebServices;
use App\Services\logErrors\ILogErrorService;
use App\Services\requestsCurls\IRequestCurlService;

class QueueEmailWebServiceService implements IQueueEmailWebServiceService
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

            $methode = 'Fichier: QueueEmailWebServiceService - Methode: getAll -';

            $endPoint = "/API/Services/get?type=MAILIN&group=" . intval($kidGroupe);

            $methodHttp = "POST";

            $dataServiceFileAttenteEmailWBs = $this->iRequestCurlService->requestCurlGeneralNotCookie($endPoint, $methodHttp, null, null);

            $serviceDecode = json_decode($dataServiceFileAttenteEmailWBs);

            $data = json_decode($serviceDecode->data);

            $this->iLogErrorService->ecrireTrace(json_encode($data), 'try', $methode, 0);

            $result = [];

            foreach ($data as $item) {
                $dataReceived = new \stdClass();
                $dataReceived->ServiceId = $item->ServiceId;
                $dataReceived->ServiceName = $item->ServiceName;
                $dataReceived->ServiceDescription = $item->ServiceDescription;
                $result[] = $dataReceived;
            }

            return $result;


        } catch (\Throwable $th) {

            $catch = 'Fichier: QueueEmailWebServiceService - Mehtode: getAll - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json($th->getMessage());
        }

    }
}
