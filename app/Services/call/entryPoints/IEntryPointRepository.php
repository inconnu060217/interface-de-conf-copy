<?php

namespace App\Services\call\entryPoints;

interface IEntryPointRepository
{

    public function getAll ($idGroupe);

    public function update ($dataReceived);
    
}
