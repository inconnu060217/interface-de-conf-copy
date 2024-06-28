<?php

namespace App\Http\Controllers;

use App\Services\call\entryPoints\IEntryPointService;
use Illuminate\Http\Request;

class EntryPointController extends Controller
{
    private IEntryPointService $IEntryPointService;
    private Request $request;

    public function __construct(Request $request, IEntryPointService $IEntryPointService)
    {
        $this->request = $request;
        $this->IEntryPointService = $IEntryPointService;
    }

    public function getAll()
    {
        $idGroupe = $this->request->query('idGroupe');
        $kidGroupe = $this->request->query('kidGroupe');
        return $this->IEntryPointService->getAll($idGroupe, $kidGroupe);
    }

    public function update(){
        $dataReceived = new \stdClass();
        $entryPointId = $this->request->query('entryPointId');
        $dataReceived->id = $entryPointId;
        $dataReceived->type = $this->request->input("data.id_type_point_entree");
        $dataReceived->kidFileAttente = $this->request->input("data.kid_file_attente");
        $dataReceived->renvoi = $this->request->input("data.renvoi");
        $dataReceived->kidSVI = $this->request->input("data.kid_svi");
        $dataReceived->numeroPresente = $this->request->input("data.numero_presente");
        return $this->IEntryPointService->update($dataReceived);
    }

}
