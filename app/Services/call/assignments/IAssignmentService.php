<?php

namespace App\Services\call\assignments;

interface IAssignmentService
{
    public function getAll ($kidGroupe);

    public function getForTypeDeterrence ($kid_groupe);

    public function getForBankMessage ();

    public function getAllExceptionalClosure($kidGroupe);

    public function updateExceptionalClosure($dataReceived);

    public function update ($dataReceived);

    public function getForAlls ($kidGroupe);

}

