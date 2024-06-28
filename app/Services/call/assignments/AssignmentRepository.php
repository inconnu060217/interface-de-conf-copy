<?php

namespace App\Services\call\assignments;
use App\Models\MessageModel;
use App\Services\requestsCurls\IRequestCurlService;

class AssignmentRepository implements IAssignmentRepository
{
    private MessageModel $messageModel;

    private IRequestCurlService $iRequestCurlService;

    public function __construct(MessageModel $messageModel, IRequestCurlService $iRequestCurlService,)
    {
        $this->messageModel = $messageModel;

        $this->iRequestCurlService = $iRequestCurlService;

    }
    public function getAll ($kid_groupe) {

        return $this->messageModel::join("banque_de_messages","message.id_banque_message","=","banque_de_messages.id_banque_message")

        ->join('type_messages', 'message.id_type_message', '=', 'type_messages.id_type_message')

        ->select("message.*", "banque_de_messages.*" , "type_messages.activable", "type_messages.modifiable", "type_messages.id_categorie_message")

        ->where('message.id_groupe', intval($kid_groupe))

        ->get()

        ->toArray();

    }

    //type de dissuasion
    public function getForTypeDeterrence ($kid_groupe) {

        return $this->messageModel::join('type_messages', 'message.id_type_message', '=', 'type_messages.id_type_message')

            ->select("message.*")

            ->where('type_messages.id_categorie_message', 5)

            ->where('message.id_groupe', intval($kid_groupe))

            ->get()

            ->toArray();

    }
    public function getForAlls ($kidGroupe){

        return $this->messageModel::where('id_groupe', intval($kidGroupe))

            ->where('nom', '!=', '0')

            ->get()

            ->toArray();

    }

    public function getAllExceptionalClosure($kidGroupe){


        $endPoint = "/API/Plannings/" . intval($kidGroupe);

        $method = "POST";

        $responsePlanningWebService = $this->iRequestCurlService->requestCurlGeneralNotCookie($endPoint, $method);

        $responseArray = json_decode($responsePlanningWebService);



        $data = json_decode($responseArray->data);

        $this->ckeckSynchroClosedRangesMessage($kidGroupe,$data);

        return $this->messageModel::join("banque_de_messages","message.id_banque_message","=","banque_de_messages.id_banque_message")

        ->join('type_messages', 'message.id_type_message', '=', 'type_messages.id_type_message')

        ->select("message.*", "banque_de_messages.*" , "type_messages.activable", "type_messages.modifiable", "type_messages.id_categorie_message")

        ->where('message.id_groupe', 99)

        ->get()

        ->toArray();

    }


    public function addSynchroClosedRangesMessage($label): void
{
    // Vérifie si le label existe déjà dans la base de données
    $existingMessage = $this->messageModel::where('nom', $label)->first();

    // Si le label n'existe pas, l'ajoute à la base de données
    if (!$existingMessage) {
        $newMessage = new $this->messageModel;
        $newMessage->nom = $label;
        $newMessage->activation = 0;
        $newMessage->id_groupe = 99;
        $newMessage->id_type_message = 2;
        $newMessage->id_banque_message = 0;
        $newMessage->save();
    }
}

    public function ckeckSynchroClosedRangesMessage($kidGroupe,$dataPlannings) {

        $labelsToDelete = [];

        $dateCurrent = new \DateTime();

    foreach ($dataPlannings as $item) {
        $closedRanges = $item->closed_ranges;

        foreach ($closedRanges as $closedRange) {
            $label = $closedRange->label;

            $startTime = \DateTime::createFromFormat('d/m/Y H:i:s', $closedRange->StartTime);

            if (!empty($label) &&  $startTime > $dateCurrent) {
                $this->addSynchroClosedRangesMessage($label);
                $labelsToDelete[] = $label;
            }
        }
    }
        //Suppression des messages du modèle où l'ID du groupe  où le nom n'est pas dans la liste des étiquettes à supprimer.
        $this->messageModel::whereIn('id_groupe', [99])->whereNotIn('nom', $labelsToDelete)->delete();

    }

    public function getForBankMessage() {

        return $this->messageModel::join('type_messages', 'message.id_type_message', '=', 'type_messages.id_type_message')

        ->select("message.id_message", "message.nom", "message.id_banque_message", "type_messages.id_categorie_message")

        ->get()

        ->toArray();

    }

    public function update ($dataReceived) {

        $updateMessage = $this->messageModel::where('id_message', '=', intval($dataReceived->id))->first();

        if($updateMessage ) {

            $updateData = [];

            $updateData['activation'] = intval($dataReceived->activation);

            $updateData['id_banque_message'] = intval($dataReceived->idBanqueMessage);

            return $updateMessage->update($updateData);
        }

        return false;

    }

    public function updateExceptionalClosure($dataReceived)
    {
        $updateMessage = $this->messageModel::where('id_message', '=', intval($dataReceived->id))->first();

        if($updateMessage ) {

            $updateData = [];

            $updateData['activation'] = intval($dataReceived->activation);

            $updateData['id_banque_message'] = intval($dataReceived->idBanqueMessage);

            return $updateMessage->update($updateData);
        }

        return false;
    }

}
