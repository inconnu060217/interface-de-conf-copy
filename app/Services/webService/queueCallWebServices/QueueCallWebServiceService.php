<?php

namespace App\Services\webService\queueCallWebServices;

use App\Services\logErrors\ILogErrorService;
use App\Services\requestsCurls\IRequestCurlService;

class QueueCallWebServiceService implements IQueueCallWebServiceService
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
            $methode = 'Fichier: QueueCallWebServiceService - Mehtode: getAll -';

            $endPoint = "/API/Services/get?type=CAMPIN&group=" . $kidGroupe;

            $methodHttp = "POST";

            $service = $this->iRequestCurlService->requestCurlGeneralNotCookie($endPoint, $methodHttp, null, null);

            $serviceDecode = json_decode($service);

            $data = json_decode($serviceDecode->data);

            $this->iLogErrorService->ecrireTrace(json_encode($data), 'try', $methode, 0);

            return $data ;

        } catch (\Throwable $th) {

            $catch = 'Fichier: QueueCallWebServiceService - Mehtode: getAll - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json( $th->getMessage());
        }

    }

    public function updateQueuePriorite ($value, $kidFileAttente) {

        try {
            $methode = 'Fichier: QueueCallWebServiceService - Mehtode: updateQueue -';

            $endPoint = "/API/Services/" . intval($kidFileAttente) . "/Service_SetWQLevel?WQLevel=" . $value;

            $methodHttp = "POST";

            $service = $this->iRequestCurlService->requestCurlGeneralNotCookie($endPoint, $methodHttp, null, null);

            $serviceDecode = json_decode($service);


            $this->iLogErrorService->ecrireTrace(json_encode($serviceDecode), 'try', $methode, 0);

            return $serviceDecode ;

        } catch (\Throwable $th) {

            $catch = 'Fichier: QueueCallWebServiceService - Mehtode: updateQueue - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json( $th->getMessage());
        }

    }

    public function updateQueueDureeDissuasionTempsReels ($value, $kidFileAttente) {

        try {
            $methode = 'Fichier: QueueCallWebServiceService - Mehtode: updateQueue -';

            $endPoint = "/API/Triggers/Services/" . intval($kidFileAttente) . "/Trigger_MAXDURATION_SetParams?maxduration=" . intval($value);

            $methodHttp = "PUT";

            $service = $this->iRequestCurlService->requestCurlGeneralNotCookie($endPoint, $methodHttp, null, null);

            $serviceDecode = json_decode($service);

            $this->iLogErrorService->ecrireTrace(json_encode($serviceDecode), 'try', $methode, 0);

            return $serviceDecode ;

        } catch (\Throwable $th) {

            $catch = 'Fichier: QueueCallWebServiceService - Mehtode: updateQueue - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json( $th->getMessage());
        }

    }

    public function getPostCallTime($kidQueueCall) {

        try {
            $methode = 'Fichier: QueueCallWebServiceService - Mehtode: getPostCallTime -';

            $endPoint = "/API/Services/" . $kidQueueCall . "/get_wrapupdelay";

            $methodHttp = "POST";

            $service = $this->iRequestCurlService->requestCurlGeneralNotCookie($endPoint, $methodHttp, null, null);

            $serviceDecode = json_decode($service);

            $data = json_decode($serviceDecode->data);

            $this->iLogErrorService->ecrireTrace(json_encode($data), 'try', $methode, 0);

            return $data ;

        } catch (\Throwable $th) {

            $catch = 'Fichier: QueueCallWebServiceService - Mehtode: getPostCallTime - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json( $th->getMessage());
        }

    }

    public function updatePostCallTime($kidQueueCall, $PostCallTime){
        try {
            $methode = 'Fichier: QueueCallWebServiceService - Mehtode: postPostCallTime -';

            $endPoint = "/API/Services/" . $kidQueueCall . "/set_wrapupdelay?wrapupdelay=" . $PostCallTime;

            $methodHttp = "POST";

            $service = $this->iRequestCurlService->requestCurlGeneralNotCookie($endPoint, $methodHttp, null, null);

            $serviceDecode = json_decode($service);

            $this->iLogErrorService->ecrireTrace(json_encode($serviceDecode), 'try', $methode, 0);

            return $serviceDecode;

        } catch (\Throwable $th) {

            $catch = 'Fichier: QueueCallWebServiceService - Mehtode: postPostCallTime - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json( $th->getMessage());
        }
    }

    public function callbackAnnonceTime($kidQueueCall,$oldCallbackAnnonceTime, $newCallbackAnnonceTime){
        try {
            //var_dump("recu ",$kidQueueCall,$oldCallbackAnnonceTime, $newCallbackAnnonceTime);

            $methode = 'Fichier: QueueCallWebServiceService - Mehtode: callbackAnnonceTime -';

            $endPoint = "/API/Triggers/Services/" . $kidQueueCall . "/Trigger_WAITING_Set?duration=" . $oldCallbackAnnonceTime;

            $methodHttp = "POST";

            $content = str_replace("'", "\'", $newCallbackAnnonceTime);

            $postBody = '{
                "duration": "' . $content . '",
                "trigger": {
                    "action": "PLAY_AUDIO"
                }
            }';

            $service = $this->iRequestCurlService->requestCurlGeneralNotCookie($endPoint, $methodHttp, $postBody, null);

            $serviceDecode = json_decode($service);


            $this->iLogErrorService->ecrireTrace(json_encode($serviceDecode), 'try', $methode, 0);

            return $serviceDecode;

        } catch (\Throwable $th) {

            $catch = 'Fichier: QueueCallWebServiceService - Mehtode: callbackAnnonceTime - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json( $th->getMessage());
        }
    }
}
