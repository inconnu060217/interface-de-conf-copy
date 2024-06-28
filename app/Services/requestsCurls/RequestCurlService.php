<?php

namespace App\Services\requestsCurls;
use App\Services\environmentParameters\IEnvironmentParameterService;
use App\Services\logErrors\ILogErrorService;

class RequestCurlService implements IRequestCurlService
{
    private ILogErrorService $iLogErrorService;

    private IEnvironmentParameterService $IEnvironmentParameterService;

    public function __construct(ILogErrorService $iLogErrorService, IEnvironmentParameterService $IEnvironmentParameterService) {

        $this->iLogErrorService = $iLogErrorService;

        $this->IEnvironmentParameterService = $IEnvironmentParameterService;

    }
    public function getParametreEnvironnement(){
        try {
            $methode = 'Fichier: RequestCurlService - Mehtode: getParametreEnvironnement -';

            $environnement = $this->IEnvironmentParameterService->getAllParameterWebService();

            $this->iLogErrorService->ecrireTrace(json_encode($environnement), 'try', $methode, 0);

            return $environnement;

        } catch (\Throwable $th) {

            $catch = 'Fichier: RequestCurlService - Mehtode: getParametreEnvironnement - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json( $th->getMessage());
        }
    }
    public function requestCurlWsUserInformation($cookieUserAuthKiamo){
        try {

            $methode = 'Fichier: RequestCurlService - Mehtode: requestCurlWsUserInformation -';

            $response = json_decode($this->getParametreEnvironnement());

            $TOKEN_WS_FOLIATEAM = "Authorization: " . $response[0]->token_ws_foliateam;

            $ULR_WS_FOLIATEAM = $response[0]->url_ws_foliateam;

            if (!$cookieUserAuthKiamo) return false;

            $url = $ULR_WS_FOLIATEAM . "/API/Users/getuser?session=" . $cookieUserAuthKiamo;

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER => array(
                    "Content-Length: 0",
                    $TOKEN_WS_FOLIATEAM
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $this->iLogErrorService->ecrireTrace(json_encode($response), 'try', $methode, 0);


            return $response == false ? false : json_decode($response, true);

        } catch (\Throwable $th) {
            $catch = 'Fichier: RequestCurlService - Mehtode: requestCurlWsUserInformation - catch Exception $th: -';
            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);
            return response()->json([
                'message' => 'Erreur dans Repository RequestCurlService methode: requestCurlWsUserInformation',
                'error' => $th->getMessage()
            ], 400);
        }
    }

    public function requestCurlGeneralNotCookie($endPoint, $method, $postBody = null, $urlKey = null){
        try {

            $methode = 'Fichier: RequestCurlService - Mehtode: requestCurlGeneralNotCookie -';

            $conf = json_decode($this->getParametreEnvironnement(), true);

            $TOKEN_WS_FOLIATEAM = "Authorization: " . $conf[0]["token_ws_foliateam"];

            $ULR_WS_FOLIATEAM = !is_null($urlKey) ? $conf[0][$urlKey] : $conf[0]["url_ws_foliateam"];

            //$ULR_WS_FOLIATEAM = $conf[0]["url_ws_foliateam"];

            if (!$endPoint && !$method) return false;

            $url = $ULR_WS_FOLIATEAM . $endPoint;

            $curl = curl_init();

            $options = array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: text/plain',
                    "Content-Length: " . strlen($postBody),
                    $TOKEN_WS_FOLIATEAM
                ),
            );

            if (!is_null($postBody)) {

                $options[CURLOPT_POSTFIELDS] = $postBody;

            }

            curl_setopt_array($curl, $options);

            $response = curl_exec($curl);

            curl_close($curl);

            $this->iLogErrorService->ecrireTrace(json_encode($response), 'try', $methode, 0);

            return $response;

        } catch (\Throwable $th) {

            $catch = 'Fichier: RequestCurlService - Mehtode: requestCurlGeneralNotCookie - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json($th->getMessage());
        }
    }

    public function requestCurlTextToSpeechFile($endPoint, $method, $dataReceived){
        try {

            $methode = 'Fichier: RequestCurlService - Mehtode: requestCurlTextToSpeech -';

            $conf = json_decode($this->getParametreEnvironnement(), true);

            $speech_recognition_kiamo = $conf[0]["speech_recognition_kiamo"];

            if (!$endPoint && !$method) return false;

            $url = $speech_recognition_kiamo . $endPoint;

            $pathWavFolder = env('PATH_TTS');

            $data = array(
                "text" => $dataReceived->text,
                "filepath" => $pathWavFolder . "first_" . $dataReceived->nameFile,
                "voice" => $dataReceived->voice,
                "gender" => $dataReceived->gender,
                "language" => $dataReceived->language,
                "speakingrate" => $dataReceived->speakingRate,
            );

            $postBody = json_encode($data);

            $curl = curl_init();

            $options = array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_POSTFIELDS => $postBody,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                )
            );

            curl_setopt_array($curl, $options);

            $response = curl_exec($curl);

            curl_close($curl);

            $this->iLogErrorService->ecrireTrace(json_encode($response), 'try', $methode, 0);

            return $response;

        } catch (\Throwable $th) {

            $catch = 'Fichier: RequestCurlService - Mehtode: requestCurlTextToSpeech - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json($th->getMessage());
        }
    }

}
