<?php

namespace App\Services\call\queueCalls;

interface IQueueCallRepository
{
    public function getAll($idGroupe);

    public function update ($dataReceived);
    
}
