<?php

namespace App\Services\environmentParameters;

interface IEnvironmentParameterService
{
    
    public function getAllParameterWebService();

    public function getAllParameterTTS();

    public function getAllParameterMainMenu();

}
