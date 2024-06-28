<?php

namespace App\Services\webService\fileWavWebService;
interface IFileWavWebServiceService {
    public function checkFileCompatibleKiamo($file, $mediaDirectory);
    public function fileConversion ($id_groupe,$dataReceived);
    public function fileConversionTextToSpeech($dataReceived);
    public function fileConversionUpdate($dataReceived);
}
