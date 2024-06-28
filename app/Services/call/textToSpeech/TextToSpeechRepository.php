<?php

namespace App\Services\call\textToSpeech;
use App\Models\SpeechSynthesisModel;

class TextToSpeechRepository implements ITextToSpeechRepository {

    private SpeechSynthesisModel $speechSynthesisModel;

    private string $pathTtsFolder;
    public function __construct(SpeechSynthesisModel $speechSynthesisModel)
    {

        $this->speechSynthesisModel = $speechSynthesisModel;

        $this->pathTtsFolder = env('PATH_TTS');


    }
    public function getAll() {

        return $this->speechSynthesisModel::all()->toArray();

    }

    public function checkNameFileExistAlready ($nameFile){

        return $this->speechSynthesisModel::where("name_file", $nameFile)->exists();

    }

    public function add($dataReceived){

        $this->speechSynthesisModel->text = $dataReceived->text;

        $this->speechSynthesisModel->name_file = $dataReceived->nameFile;

        $this->speechSynthesisModel->voice = $dataReceived->voice;

        $this->speechSynthesisModel->gender = $dataReceived->gender;

        $this->speechSynthesisModel->language = $dataReceived->language;

        $this->speechSynthesisModel->speaking_rate = $dataReceived->speakingRate;

        $this->speechSynthesisModel->save();

        return $this->speechSynthesisModel;
    }
    public function delete($id){

        $deleteById = $this->speechSynthesisModel::where('id', '=', $id)->first();

        $oldFile = $this->pathTtsFolder . $deleteById->name_file;

        if (file_exists($oldFile)) {

            unlink($oldFile);

        }

        $deleteById->delete();

        return $deleteById;

    }
}
