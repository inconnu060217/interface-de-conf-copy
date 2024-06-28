<?php

namespace App\Services\call\queueCalls;

use App\Models\QueueCallModel;
use App\Services\webService\queueCallWebServices\IQueueCallWebServiceService;

class QueueCallRepository implements IQueueCallRepository
{
    private QueueCallModel $queueCallModel;

    private IQueueCallWebServiceService $IQueueCallWebServiceService;

    public function __construct(QueueCallModel $queueCallModel,IQueueCallWebServiceService $IQueueCallWebServiceService,)
    {

        $this->queueCallModel = $queueCallModel;

        $this->IQueueCallWebServiceService = $IQueueCallWebServiceService;

    }
    public function getAll($idGroupe)
    {
        return $this->queueCallModel::leftJoin("type_debordement", "file_attente.id_type_debordement", "=", "type_debordement.id_debordement")
            ->leftJoin("message", "message.id_message","=","file_attente.id_debordement_message")
            ->select("file_attente.id_file_attente",
            "file_attente.kid_file_attente",
            "file_attente.kid_planning_fa",
            "file_attente.id_debordement_message",
            "file_attente.duree_dissuasion_tae",
            "file_attente.duree_dissuasion_reel",
            "file_attente.ratio_agent_attente",
            "file_attente.id_type_debordement",
            "file_attente.renvoi",
            "file_attente.callback_annonce_time",
            "file_attente.kid_file_attente_debordement",
            "file_attente.callback_attente",
            "file_attente.numero_presente",
            "type_debordement.nom as nom_debordement",
            "message.id_message",
            "message.nom as nom_debordement_message")

            ->where('file_attente.id_groupe', $idGroupe)

            ->get()

            ->toArray();
    }

    public function update($dataReceived)
    {
        $updateFileAttente = $this->queueCallModel::where('id_file_attente', '=', intval($dataReceived->id))->first();

        if($updateFileAttente) {

            $updateData = [];

            $this->IQueueCallWebServiceService->updateQueuePriorite($dataReceived->priorite, $updateFileAttente->kid_file_attente);

            $this->IQueueCallWebServiceService->updatePostCallTime($updateFileAttente->kid_file_attente, $dataReceived->tempsPostAppel);

            $this->IQueueCallWebServiceService->callbackAnnonceTime($updateFileAttente->kid_file_attente, $updateFileAttente->callback_annonce_time, $dataReceived->tempsPropositionCallabck);

            $this->IQueueCallWebServiceService->updateQueueDureeDissuasionTempsReels($dataReceived->dureeDissuasionReel, $updateFileAttente->kid_file_attente);

            $updateData['kid_planning_fa'] = intval($dataReceived->kidPlanningfa);

            $updateData['duree_dissuasion_tae'] = intval($dataReceived->dureeDissuasionTae);

            $updateData['duree_dissuasion_reel'] = intval($dataReceived->dureeDissuasionReel);

            $updateData['ratio_agent_attente'] = intval($dataReceived->ratioAgentAttente);

            $updateData['id_type_debordement'] = intval($dataReceived->idTypeDebordement);

            $updateData['callback_annonce_time'] = $dataReceived->tempsPropositionCallabck;

            $updateData['renvoi'] = $dataReceived->renvoi;

            $updateData['kid_file_attente_debordement'] = intval($dataReceived->kidFileAttenteDebordement);

            $updateData['id_debordement_message'] = intval($dataReceived->idDebordementMessage);

            $updateData['callback_attente'] = intval($dataReceived->callbackAttente);

            $updateData['numero_presente'] = $dataReceived->numeroPresente;

            return $updateFileAttente->update($updateData);

        }

        return false;

    }
}
