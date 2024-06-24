<?php

namespace App\Services\logErrors;

interface ILogErrorService 
{
    public function ecrireTrace($message, $gravite = "debug", $sourcePhp = "", $callRef = 0);
    public function fermerTraces();
    public function deleteOldFiles ($nbDays, $sourcePhp = "", $callRef = 0);
}