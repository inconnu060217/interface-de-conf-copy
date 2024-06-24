<?php

namespace App\Services\environmentParameters;

interface IEnvironmentParameterRepository
{
    
    public function getAllParameterWebService();

    public function getAllParameterTTS();

    public function getAllParameterMainMenu();

}
