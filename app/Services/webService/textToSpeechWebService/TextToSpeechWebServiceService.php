<?php

namespace App\Services\webService\textToSpeechWebService;;
use App\Services\connexionsKiamos\IConnexionKiamoService;
use App\Services\logErrors\ILogErrorService;
use App\Services\requestsCurls\IRequestCurlService;
use App\Services\webService\fileWavWebService\IFileWavWebServiceService;

class TextToSpeechWebServiceService implements ITextToSpeechWebServiceService
{
    private ILogErrorService $iLogErrorService;

    private IRequestCurlService $iRequestCurlService;

    private IFileWavWebServiceService $iFileWavWebServiceService;

    public function __construct(

        ILogErrorService $iLogErrorService,

        IRequestCurlService $iRequestCurlService,

        IFileWavWebServiceService $iFileWavWebServiceService

        )
    {
        $this->iLogErrorService = $iLogErrorService;

        $this->iFileWavWebServiceService = $iFileWavWebServiceService;

        $this->iRequestCurlService = $iRequestCurlService;

    }
    public function TTSSynthesis ($dataReceived) {
        try {

            $methode = 'Fichier: TextToSpeechWebServiceService - method: TTSSynthesis -';

            $endPoint = "/API/TTS/Synthesis";

            $methodHttp = "POST";

            $response = $this->iRequestCurlService->requestCurlTextToSpeechFile($endPoint, $methodHttp, $dataReceived);

            $this->iLogErrorService->ecrireTrace(json_encode($dataReceived), 'try', $methode, 0);

            if(gettype($response) === "string" && strlen($response) === 0) {

                return $this->iFileWavWebServiceService->fileConversionTextToSpeech($dataReceived);

            }

        } catch (\Throwable $th) {

            $catch = 'Fichier: TextToSpeechWebServiceService - Mehtode: TTSSynthesis - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json( $th->getMessage());
        }
    }
}
