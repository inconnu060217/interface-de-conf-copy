<?php

namespace App\Services\call\queueCalls;

interface IQueueCallService
{
    public function getAll($idGroupe, $kidGroupe);

    public function getAllQueueWebServices ($kidGroupe);

    public function update ($dataReceived);
    
}