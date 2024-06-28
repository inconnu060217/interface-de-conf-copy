<?php

namespace App\Services\call\assignments;

interface IAssignmentRepository
{

    public function getAll ($kid_groupe);

    public function getForTypeDeterrence ($kid_groupe);

    public function getForBankMessage();

    public function getAllExceptionalClosure($kidGroupe);

    public function updateExceptionalClosure($dataReceived);

    public function update ($dataReceived);

    public function getForAlls ($kidGroupe);

}
