<?php

namespace App\Services\environmentParameters;
use App\Models\EnvironmentalParameterModel;
use App\Services\environmentParameters\IEnvironmentParameterRepository;

class EnvironmentParameterRepository implements IEnvironmentParameterRepository
{
    public function getAllParameterWebService(){

        return EnvironmentalParameterModel::select('token_ws_foliateam','url_ws_foliateam', 'audio_tools_kiamo', 'speech_recognition_kiamo')->get();

    }
    public function getAllParameterTTS() {

        return EnvironmentalParameterModel::select('tts')->get();

    }
    public function getAllParameterMainMenu(){

        return EnvironmentalParameterModel::select('call','email','chat','messaging','accueil', "advanced")->get();

    }
}
