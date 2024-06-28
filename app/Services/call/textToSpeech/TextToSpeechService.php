<?php

namespace App\Services\call\textToSpeech;

use App\Services\logErrors\ILogErrorService;
use App\Services\webService\textToSpeechWebService\ITextToSpeechWebServiceService;


class TextToSpeechService implements ITextToSpeechService {

    private ILogErrorService $iLogErrorService;

    private ITextToSpeechRepository $ITextToSpeechRepository;

    private ITextToSpeechWebServiceService $ITextToSpeechWebServiceService;

    public function __construct(
        ILogErrorService $iLogErrorService,
        ITextToSpeechRepository $ITextToSpeechRepository,
        ITextToSpeechWebServiceService $ITextToSpeechWebServiceService

    ) {
        $this->iLogErrorService = $iLogErrorService;
        $this->ITextToSpeechRepository = $ITextToSpeechRepository;
        $this->ITextToSpeechWebServiceService = $ITextToSpeechWebServiceService;
    }

    public function getAll()
    {
        try {
            $methode = 'Fichier: TextToSpeechService - Mehtode: getAll -';

            $response = $this->ITextToSpeechRepository->getAll();

            $this->iLogErrorService->ecrireTrace(json_encode($response), 'try', $methode, 0);

            return $response;


        } catch (\Throwable $th) {

            $catch = 'Fichier: TextToSpeechService - Mehtode: getAll - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json($th->getMessage());
        }
    }
    public function add($dataReceived) {
        try {
            $methode = 'Fichier: TextToSpeechService - Mehtode: add -';

            $this->iLogErrorService->ecrireTrace(json_encode($dataReceived), 'try', $methode, 0);

            if($this->ITextToSpeechRepository->checkNameFileExistAlready($dataReceived->nameFile) === true) {

                return response()->json(['status' => 409, 'message' => 'Le nom du fichier existe déjà.']);

            } else {

                $responseWebService = $this->ITextToSpeechWebServiceService->TTSSynthesis($dataReceived);

               if(gettype($responseWebService) === "string" && strlen($responseWebService) !== 0) {

                   return $this->ITextToSpeechRepository->add($dataReceived);

               } else {

                  return response()->json(['status' => 409, 'message' => 'Une erreur s\'est produite lors de la requête.']);

               }

            }

        } catch (\Throwable $th) {

            $catch = 'Fichier: TextToSpeechService - Mehtode: add - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json($th->getMessage());
        }
    }

    public function delete($id) {
            try {
                $methode = 'Fichier: TextToSpeechService - Mehtode: delete -';

                $this->iLogErrorService->ecrireTrace(json_encode($id), 'try', $methode, 0);

                $this->ITextToSpeechRepository->delete($id);

                return response()->json(['message' => 'La synthése vocale a bien été supprimé.']);

            } catch (\Throwable $th) {

                $catch = 'Fichier: TextToSpeechService - Mehtode: delete - catch Exception $th: -';

                $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

                return response()->json($th->getMessage());

            }
        }

    public function playAudio ($dataReceived) {
        try {

            $methode = 'Fichier: TextToSpeechService - Mehtode: playAudio -';

            $pathWavFolder = env('PATH_TTS');

            $path = $pathWavFolder . $dataReceived->fileName;

            $this->iLogErrorService->ecrireTrace(json_encode($dataReceived), 'try', $methode, 0);

            if (file_exists($path)) {
                return response()->file($path);
            } else {
                throw new \Exception('le fichier audio n`\'a pas été trouvé.', 409);
            }

        } catch (\Throwable $th) {

            $catch = 'Fichier: TextToSpeechService - Mehtode: playAudio - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json($th->getMessage());
        }
    }
    public function download($dataReceived){
        $pathWavFolder = env('PATH_TTS');

        $path = $pathWavFolder . $dataReceived->fileName;

        if (file_exists($path)) {

            return response()->download($path, null, [], null);
        } else {
            abort(404);
        }
    }
}
