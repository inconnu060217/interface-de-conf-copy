<?php

namespace App\Services\webService\fileWavWebService;
use App\Services\call\libraries\ILibraryRepository;
use App\Services\logErrors\ILogErrorService;
use App\Services\requestsCurls\IRequestCurlService;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Audio\Wav;
class FileWavWebServiceService implements IFileWavWebServiceService {
    private ILogErrorService $iLogErrorService;
    private IRequestCurlService $iRequestCurlService;

    private ILibraryRepository $ILibraryRepository;

    private $pathWavFolder;
    public function __construct(
        ILogErrorService $iLogErrorService,

        IRequestCurlService $iRequestCurlService,

        ILibraryRepository $ILibraryRepository
    )
    {
        $this->iLogErrorService = $iLogErrorService;

        $this->iRequestCurlService = $iRequestCurlService;

        $this->pathWavFolder = env('PATH_WAV');

        $this->ILibraryRepository = $ILibraryRepository;

    }

    public function checkFileCompatibleKiamo ( $file, $mediaDirectory) {
        try {
            $methode = 'Fichier: FileWavWebServiceService - Mehtode: checkFileCompatibleKiamo -';

            $fileName = $file->getClientOriginalName();

            $directoryMedia = $this->pathWavFolder . $mediaDirectory . "/";

            $pathAbsolute = $file->move($directoryMedia, $fileName);

            $endPoint = "/API/Audio/AudioDescription?source=" . $pathAbsolute;

            $methodHttp = "POST";

            $service = $this->iRequestCurlService->requestCurlGeneralNotCookie($endPoint, $methodHttp, null, "audio_tools_kiamo");

            $serviceDecode = json_decode($service);

           if($serviceDecode->kiamocompatible === false) {

               $deleteFile = $directoryMedia . $fileName;

              if (file_exists($deleteFile)) {

                  unlink($deleteFile);
              }

           }

            $this->iLogErrorService->ecrireTrace(json_encode($file), 'try', $methode, 0);

            return $serviceDecode;

        } catch (\Throwable $th) {

            $catch = 'Fichier: QueueCallWebServiceService - Mehtode: FileWavWebServiceService - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json( $th->getMessage());
        }
    }

    public function fileConversion($id_groupe,$dataReceived) {
        try {
            $methode = 'Fichier: FileWavWebServiceService - Mehtode: fileConversion -';

            $file = $dataReceived->file;

            $fileName = $file->getClientOriginalName();

            $directoryMedia = $this->pathWavFolder . $dataReceived->mediaDirectory . "/";

            $sourcePathAbsolute = $file->move($directoryMedia, $fileName);

            $destinationPathAbsolute = $directoryMedia . $dataReceived->sourceDestination;

            $endPoint = "/API/Audio/ConvertToKiamo?source=" . $sourcePathAbsolute . "&destination=" . $destinationPathAbsolute;

            $methodHttp = "POST";

            $service = $this->iRequestCurlService->requestCurlGeneralNotCookie($endPoint, $methodHttp, null, "audio_tools_kiamo");

            $serviceDecode = json_decode($service);

            // Vérifie si le service a retourné un objet avec une propriété 'status' égale à 500
            if(is_object($serviceDecode) && property_exists($serviceDecode, 'status') && $serviceDecode->status === 500) {

                if (file_exists($sourcePathAbsolute)) {

                    unlink($sourcePathAbsolute);
                }

                throw new \Exception(500);

                // Si le statut n'est pas 500
            } else {

                // Vérifie si le fichier source existe
                if (file_exists($sourcePathAbsolute)) {

                    unlink($sourcePathAbsolute);
                }

                $newData = $this->ILibraryRepository->addWAV($id_groupe,$dataReceived);

                $this->iLogErrorService->ecrireTrace(json_encode($dataReceived), 'try', $methode, 0);

                return response()->json($newData);

            }

        } catch (\Throwable $th) {

            $catch = 'Fichier: QueueCallWebServiceService - Mehtode: fileConversion - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json( $th->getMessage());
        }

    }
    public function fileConversionUpdate($dataReceived) {
        try {
            $methode = 'Fichier: FileWavWebServiceService - Mehtode: fileConversionUpdate -';

            $file = $dataReceived->file;

            $fileName = $file->getClientOriginalName();

            $directoryMedia = $this->pathWavFolder . $dataReceived->mediaDirectory . "/";

            $sourcePathAbsolute = $file->move($directoryMedia, $fileName);

            $destinationPathAbsolute = $directoryMedia . $dataReceived->sourceDestination;

            $endPoint = "/API/Audio/ConvertToKiamo?source=" . $sourcePathAbsolute . "&destination=" . $destinationPathAbsolute;

            $methodHttp = "POST";

            $service = $this->iRequestCurlService->requestCurlGeneralNotCookie($endPoint, $methodHttp, null, "audio_tools_kiamo");

            $serviceDecode = json_decode($service);

            // Vérifie si le service a retourné un objet avec une propriété 'status' égale à 500
            if(is_object($serviceDecode) && property_exists($serviceDecode, 'status') && $serviceDecode->status === 500) {

                if (file_exists($sourcePathAbsolute)) {

                    unlink($sourcePathAbsolute);
                }

                throw new \Exception(500);

                // Si le statut n'est pas 500
            } else {

                // Vérifie si le fichier source existe
                if (file_exists($sourcePathAbsolute)) {

                    unlink($sourcePathAbsolute);
                }

                 $this->ILibraryRepository->updateWAV($dataReceived);

                $this->iLogErrorService->ecrireTrace(json_encode($dataReceived), 'try', $methode, 0);

                return response()->json(['status' => 200,'message' => 'Le message a bien été mise à jour.']);

            }

        } catch (\Throwable $th) {

            $catch = 'Fichier: QueueCallWebServiceService - Mehtode: fileConversionUpdate - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json( $th->getMessage());
        }

    }

    public function fileConversionTextToSpeech($dataReceived) {
        try {
            $methode = 'Fichier: FileWavWebServiceService - Mehtode: fileConversionTextToSpeech -';

            $pathWavFolder = env('PATH_TTS');

            $sourcePathAbsolute = $pathWavFolder . "first_" . $dataReceived->nameFile;

            $destinationPathAbsolute = $pathWavFolder . $dataReceived->nameFile;

            $endPoint = "/API/Audio/ConvertToKiamo?source=" . $sourcePathAbsolute . "&destination=" . $destinationPathAbsolute;

            $methodHttp = "POST";

            $service = $this->iRequestCurlService->requestCurlGeneralNotCookie($endPoint, $methodHttp, null, "audio_tools_kiamo");

            $serviceDecode = json_decode($service);


            // Vérifie si le service a retourné un objet avec une propriété 'status' égale à 500
            if(is_object($serviceDecode) && property_exists($serviceDecode, 'status') && $serviceDecode->status === 500) {

                if (file_exists($sourcePathAbsolute)) {

                    unlink($sourcePathAbsolute);
                }

                throw new \Exception(500);

                // Si le statut n'est pas 500
            } else {

                // Vérifie si le fichier source existe
                if (file_exists($sourcePathAbsolute)) {

                    unlink($sourcePathAbsolute);
                }

                $this->iLogErrorService->ecrireTrace(json_encode($dataReceived), 'try', $methode, 0);

                return $service;

            }

        } catch (\Throwable $th) {

            $catch = 'Fichier: QueueCallWebServiceService - Mehtode: fileConversionTextToSpeech - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json( $th->getMessage());
        }

    }
}
