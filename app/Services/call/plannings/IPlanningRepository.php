<?php

namespace App\Services\call\plannings;

interface IPlanningRepository
{
    public function getAll($id_groupe, $id);

    public function getAllWebService($id_groupe);

    public function update($dataReceived);

    public function getForBankMessage();

}
