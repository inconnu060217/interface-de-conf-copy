<?php

namespace App\Services\call\libraries;
use App\Models\BankMessageModel;
use Illuminate\Http\JsonResponse;

class LibraryRepository implements ILibraryRepository
{
    private BankMessageModel $bankMessageModel;

    private string $pathWavFolder;

    public function __construct(BankMessageModel $bankMessageModel)
    {

        $this->bankMessageModel = $bankMessageModel;

        $this->pathWavFolder = env('PATH_WAV');

    }
    public function getAll ($idGroupe){

        return  $this->bankMessageModel::join("type_diffusion","banque_de_messages.id_type_diffusion", "=", "type_diffusion.id_type_diffusion")

        ->leftJoin("lang","banque_de_messages.id_lang", "=", "lang.id_lang")

        ->where('banque_de_messages.id_groupe', $idGroupe)

        ->orderBy('banque_de_messages.created_at', 'asc')

        ->get()

        ->toArray();

    }
    public function checkLibraryExistAlready ($id_groupe, $name){

        return $this->bankMessageModel::where("nom_personnalise", $name)->where("id_groupe", intval($id_groupe))->exists();

    }
    public function addTTS ($id_groupe, $dataReceived) {

        $this->bankMessageModel->nom_personnalise = $dataReceived->name;

        $this->bankMessageModel->id_groupe = $id_groupe;

        $this->bankMessageModel->contenu_tts = $dataReceived->contenuTTS;

        $this->bankMessageModel->id_type_diffusion = intval($dataReceived->typeDiffusion);

        $this->bankMessageModel->id_lang = intval($dataReceived->langue);

        $this->bankMessageModel->save();

        $newData =  $this->bankMessageModel;

        $newData['utilisation'] = false;

        $newData['nomsUtilisation'] = [];

        $newData['nom_diffusion'] = 'synthèse vocale';

        return $newData;

    }
    public function addWAV ($id_groupe, $dataReceived){

        $file = $dataReceived->file;

        $fileName = $file->getClientOriginalName();

        $this->bankMessageModel->nom_personnalise = $dataReceived->name;

        $this->bankMessageModel->id_groupe = $id_groupe;

        $this->bankMessageModel->path_banque_message = $dataReceived->mediaDirectory . "/" . (property_exists($dataReceived, 'sourceDestination') ? $dataReceived->sourceDestination :  $fileName);

        $this->bankMessageModel->id_type_diffusion = intval($dataReceived->typeDiffusion);

        $this->bankMessageModel->id_lang = null;

        $this->bankMessageModel->save();

        $newData =  $this->bankMessageModel;

        $newData['utilisation'] = false;

        $newData['nomsUtilisation'] = [];

        $newData['nom_diffusion'] = 'message audio';

        return $newData;

    }
    public function updateTTS( $dataReceived){
        $updateByid = $this->bankMessageModel::where('id_banque_message', '=', $dataReceived->id)->first();

        if($updateByid ) {

            $updateData = [];

            $updateData['contenu_tts'] = $dataReceived->contenuTTS;

            return $updateByid->update($updateData);
        }

        return false;

    }
    public function updateWAV( $dataReceived){

        $file = $dataReceived->file;

        $fileName = $file->getClientOriginalName();

        $updateByid = $this->bankMessageModel::where('id_banque_message', '=', $dataReceived->id)->first();

            $oldFile = $this->pathWavFolder . $updateByid->path_banque_message;


            if (file_exists($oldFile)) {

                unlink($oldFile);
            }

        if($updateByid ) {

            $updateData = [];

            $updateData['path_banque_message'] = $dataReceived->mediaDirectory . "/" . (property_exists($dataReceived, 'sourceDestination') ? $dataReceived->sourceDestination : $fileName);;

            return $updateByid->update($updateData);
        }

        return false;

    }
    public function delete ($dataReceived){

        $deleteById = $this->bankMessageModel::where('id_banque_message', '=', $dataReceived->id)->first();

        if($deleteById->path_banque_message){

            $oldFile = $this->pathWavFolder . $deleteById->path_banque_message;

            if (file_exists($oldFile)) {

                unlink($oldFile);

            }

            $deleteById->delete();

        }
        if($deleteById->contenu_tts) {

            $deleteById->delete();

        }

        return $deleteById;

    }
}
