<?php

namespace App\Services\call\plannings;

interface IPlanningService
{
    public function getAll($id, $idGroupe, $kidGroupe);

    public function getAllWebService($idGroupe);

    public function update($dataReceived);

    public function getForBankMessage($kidGroupe);

}
