<?php

namespace App\Services\call\libraries;
use App\Services\call\assignments\IAssignmentService;
use App\Services\call\plannings\IPlanningService;
use App\Services\environmentParameters\IEnvironmentParameterService;
use App\Services\webService\fileWavWebService\IFileWavWebServiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\logErrors\ILogErrorService;
use App\Services\connexionsKiamos\IConnexionKiamoService;

class LibraryService implements ILibraryService
{
    private Request $request;

    private ILogErrorService $iLogErrorService;

    private ILibraryRepository $ILibraryRepository;


    private IEnvironmentParameterService $IEnvironmentParameterService;

    private IAssignmentService $IAssignmentService;

    private IFileWavWebServiceService $iFileWavWebServiceService;

    public function __construct(
        Request $request,

        ILogErrorService $iLogErrorService,

        ILibraryRepository $ILibraryRepository,


        IEnvironmentParameterService $IEnvironmentParameterService,

        IAssignmentService $IAssignmentService,

        IFileWavWebServiceService $iFileWavWebServiceService

        )
    {
        $this->request = $request;

        $this->iLogErrorService = $iLogErrorService;

        $this->ILibraryRepository = $ILibraryRepository;


        $this->IEnvironmentParameterService = $IEnvironmentParameterService;

        $this->IAssignmentService = $IAssignmentService;

        $this->iFileWavWebServiceService = $iFileWavWebServiceService;

    }

    public function getAll ($kidGroupe): array
    {
        try {
            $methode = 'Fichier: LibraryService - Mehtode: getAll -';


            $assignments = $this->IAssignmentService->getForBankMessage();

            $environment = $this->IEnvironmentParameterService->getAllParameterTTS();

            $libraries = $this->ILibraryRepository->getAll($kidGroupe);


            //recuperation des noms de assignments utilisant les banques de messages
            $resultLibraries = [];

            if(is_array($libraries) && !empty($libraries) && is_array($assignments) && !empty($assignments)) {

                $resultLibraries = array_map(function ($item) use ($assignments) {

                    $utilisations = array_filter($assignments, function ($element) use ($item) {
                        return intval($element['id_banque_message']) === intval($item['id_banque_message']);
                    });

                    if (count($utilisations) > 0) {

                        $nomsUtilisation = [];

                        foreach ($utilisations as $utilisation) {

                            if (isset($utilisation['nom']) && isset($utilisation['id_categorie_message'])) {

                                $objUtilisation = new \stdClass();

                                $objUtilisation->nom = $utilisation['nom'];

                                $objUtilisation->id_categorie_message = $utilisation['id_categorie_message'];

                                $nomsUtilisation[] = $objUtilisation;
                            }
                        }

                        return array_merge($item, ['utilisation' => true, 'nomsUtilisation' => $nomsUtilisation]);
                    } else {
                        return array_merge($item, ['utilisation' => false, 'nomsUtilisation' => []]);
                    }
                }, $libraries);

            }

            $response = [

                "banquesMessages" =>$resultLibraries,

                'environment' => $environment,
            ];

            $this->iLogErrorService->ecrireTrace(json_encode($response), 'try', $methode, 0);

            return $response;

        } catch (\Throwable $th) {

            $catch = 'Fichier: LibraryService - Mehtode: getAll - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return [$th->getMessage()];
        }
    }
    public function add ($id_groupe,$dataReceived){
        try {
            $methode = 'Fichier: LibraryService - Mehtode: add -';

            $this->iLogErrorService->ecrireTrace(json_encode($dataReceived), 'try', $methode, 0);

            if($this->ILibraryRepository->checkLibraryExistAlready($id_groupe,$dataReceived->name)) {

                return response()->json(['status' => 409, 'message' => 'Ce nom de message existe déjà.']);

            }

            if(!empty($dataReceived->contenuTTS) && $dataReceived->type === "TTS") {

                $newData = $this->ILibraryRepository->addTTS($id_groupe, $dataReceived);

                return response()->json($newData);

            }

            if($this->request->hasFile("path_banque_message") && $dataReceived->type === "WAV") {

                $responseCheckCompatible = $this->iFileWavWebServiceService->checkFileCompatibleKiamo($dataReceived->file, $dataReceived->mediaDirectory);

                if (!property_exists($responseCheckCompatible, 'kiamocompatible')) {

                    return response()->json(['status' => 409, 'message' => 'Une erreur s\'est produite lors de la requête.']);

                }

                if($responseCheckCompatible->kiamocompatible === false) {

                    $channels = $responseCheckCompatible->channels === 2 ? " stéréo" : " mono";

                    $message = $responseCheckCompatible->message . ' ( ' . $responseCheckCompatible->encoding . " " . $responseCheckCompatible->bitspersample . "bits - " . $channels . " " . $responseCheckCompatible->samplerate . " KHz )";

                    return response()->json(['status' => 422, 'message' => $message ]);

                }

                else {

                    $newData = $this->ILibraryRepository->addWAV($id_groupe,$dataReceived);

                    return response()->json($newData);

                }

            }

            return null;

        } catch (\Throwable $th) {

            $catch = 'Fichier: LibraryService - Mehtode: add - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json( $th->getMessage());
        }
    }
    public function update ($dataReceived){
        try {

            $methode = 'Fichier : LibraryService - Mehtode: update -';

            $this->iLogErrorService->ecrireTrace(json_encode($dataReceived), 'try', $methode, 0);

            if(!empty($dataReceived->contenuTTS) && $dataReceived->type === "TTS") {

                $response = $this->ILibraryRepository->updateTTS($dataReceived);

                if($response === true) {

                    return response()->json(['status' => 200,'message' => 'Le message a bien été mise à jour.']);

                }

                else {

                    return response()->json(['status' => 400, 'message' => "Une erreur s'est produite lors de la requête. Veuillez ressayer."]);

                }
            }

            if ($this->request->hasFile("path_banque_message") && $dataReceived->type === "WAV") {

                $responseCheckCompatible = $this->iFileWavWebServiceService->checkFileCompatibleKiamo($dataReceived->file, $dataReceived->mediaDirectory);

                if (!property_exists($responseCheckCompatible, 'kiamocompatible')) {

                    return response()->json(['status' => 409, 'message' => 'Une erreur s\'est produite lors de la requête.']);

                }

                if($responseCheckCompatible->kiamocompatible === false) {

                    $channels = $responseCheckCompatible->channels === 2 ? " stéréo" : " mono";

                    $message = $responseCheckCompatible->message . ' ( ' . $responseCheckCompatible->encoding . " " . $responseCheckCompatible->bitspersample . "bits - " . $channels . " " . $responseCheckCompatible->samplerate . " KHz )";

                    return response()->json(['status' => 422, 'message' => $message ]);

                }

                else {

                    $response = $this->ILibraryRepository->updateWAV($dataReceived);

                    if($response === true) {

                        return response()->json(['status' => 200,'message' => 'Le message a bien été mise à jour.']);

                    }

                    else {

                        return response()->json(['status' => 400, 'message' => "Une erreur s'est produite lors de la requête. Veuillez ressayer."]);

                    }
                }

            }

            return null;

        } catch (\Throwable $th) {

            $catch = 'Fichier: LibraryService - Mehtode: update - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json( $th->getMessage());
        }
    }
    public function delete ($dataReceived) {
        try {

            $methode = 'Fichier: LibraryService - Mehtode: delete -';

            $this->iLogErrorService->ecrireTrace(json_encode($dataReceived), 'try', $methode, 0);

            $this->ILibraryRepository->delete($dataReceived);

            return response()->json(['message' => 'Le message a bien été supprimé.']);

        } catch (\Throwable $th) {

            $catch = 'Fichier: LibraryService - Mehtode: delete - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json( $th->getMessage());
        }
    }
    public function playAudio ($dataReceived) {
        try {

            $methode = 'Fichier: LibraryService - Mehtode: playAudio -';

            $pathWavFolder = env('PATH_WAV');

            $path = $pathWavFolder . $dataReceived->directoryMedia . "/" . $dataReceived ->fileName;

            $this->iLogErrorService->ecrireTrace(json_encode($dataReceived), 'try', $methode, 0);

            if (file_exists($path)) {
                return response()->file($path);
            } else {
                throw new \Exception('le fichier audio n`\'a pas été trouvé.', 409);
            }

        } catch (\Throwable $th) {

            $catch = 'Fichier: LibraryService - Mehtode: playAudio - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json($th->getMessage());
        }
    }
}
