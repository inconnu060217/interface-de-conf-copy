<?php

namespace App\Http\Controllers;

use App\Services\call\queueCalls\IQueueCallService;
use Illuminate\Http\Request;

class QueueCallController extends Controller
{
    private Request $request;
    private IQueueCallService $IQueueCallService;

    public function __construct(
        Request $request,
        IQueueCallService $IQueueCallService

        )
    {
        $this->request = $request;
        $this->IQueueCallService = $IQueueCallService;
    }
    public function getAll () {
        $idGroupe = $this->request->query('idGroupe');
        $kidGroupe = $this->request->query('kidGroupe');
        return $this->IQueueCallService->getAll(intval($idGroupe), intval($kidGroupe));
    }
    public function getAllQueueWebServices () {
        $kidGroupe = $this->request->query('kidGroupe');
        return $this->IQueueCallService->getAllQueueWebServices(intval($kidGroupe));
    }
    public function update($id) {
        $dataReceived = new \stdClass();
        $dataReceived->id = $id;
        $dataReceived->kidPlanningfa = $this->request->input("kid_planning_fa");
        $dataReceived->dureeDissuasionTae = $this->request->input("duree_dissuasion_tae");
        $dataReceived->dureeDissuasionReel = $this->request->input("duree_dissuasion_reel");
        $dataReceived->ratioAgentAttente = $this->request->input("ratio_agent_attente");
        $dataReceived->idTypeDebordement = $this->request->input("id_type_debordement");
        $dataReceived->renvoi = $this->request->input("renvoi");
        $dataReceived->kidFileAttenteDebordement = $this->request->input("kid_file_attente_debordement");
        $dataReceived->callbackAttente = $this->request->input("callback_attente");
        $dataReceived->idDebordementMessage = $this->request->input("id_debordement_message");
        $dataReceived->priorite = $this->request->input("priorite");
        $dataReceived->numeroPresente = $this->request->input("numero_presente");
        $dataReceived->tempsPostAppel = $this->request->input("temps_post_appel");
        $dataReceived->tempsPropositionCallabck = $this->request->input("temps_proposition_callabck");
        return $this->IQueueCallService->update($dataReceived);
    }

}
