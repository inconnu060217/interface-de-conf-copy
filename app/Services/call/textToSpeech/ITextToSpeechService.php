<?php

namespace App\Services\call\textToSpeech;

interface ITextToSpeechService {
    public function getAll();
    public function add($dataReceived);
    public function delete($id);

    public function playAudio ($dataReceived);

    public function download($dataReceived);
}
