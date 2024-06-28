<?php

namespace App\Services\call\entryPoints;

interface IEntryPointService
{
    
    public function getAll ($idGroupe, $kidGroupe);

    public function update ($dataReceived);

}
