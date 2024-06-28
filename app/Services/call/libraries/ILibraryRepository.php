<?php

namespace App\Services\call\libraries;

interface ILibraryRepository
{
    public function getAll ($kidGroupe);

    public function checkLibraryExistAlready ($idGroupe,$name);

    public function addTTS ($id_groupe,$dataReceived);

    public function addWAV ($id_groupe,$dataReceived);

    public function updateTTS($dataReceived);

    public function updateWAV( $dataReceived);

    public function delete ($dataReceived);

}
