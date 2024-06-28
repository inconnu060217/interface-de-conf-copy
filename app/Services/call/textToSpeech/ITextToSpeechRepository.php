<?php

namespace App\Services\call\textToSpeech;

interface ITextToSpeechRepository {
    public function getAll();
    public function add($dataReceived);
    public function checkNameFileExistAlready($nameFile);
    public function delete($id);
}
