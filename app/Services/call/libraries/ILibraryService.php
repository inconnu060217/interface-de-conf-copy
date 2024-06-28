<?php

namespace App\Services\call\libraries;

interface ILibraryService
{
    public function getAll ($kidGroupe);

    public function add ($id_groupe,$dataReceived);

    public function update ($dataReceived);

    public function delete ($dataReceived);

    public function playAudio ($dataReceived);
    
}
