<?php

namespace App\Services\call\entryPoints;
use App\Models\EntryPointModel;


class EntryPointRepository implements IEntryPointRepository
{
    private EntryPointModel $entryPointModel;

    function __construct(EntryPointModel $entryPointModel)
    {

        $this->entryPointModel = $entryPointModel;

    }
    public function getAll ($idGroupe){

        return $this->entryPointModel::join("type_point_entree", "point_entree.id_type_point_entree", "=", "type_point_entree.id_type_point_entree")

            ->where('id_groupe', '=', $idGroupe)

            ->get()

            ->toArray();

    }
    public function update ($dataReceived){
        $updatePointEntree = $this->entryPointModel::where('id_point_entree', '=', intval($dataReceived->id))->first();

        if($updatePointEntree) {

            $updateData = [];

            $updateData['id_type_point_entree'] = intval($dataReceived->type);

            $updateData['kid_file_attente'] = intval($dataReceived->kidFileAttente);

            $updateData['kid_svi'] = intval($dataReceived->kidSVI);

            $updateData['numero_presente'] = $dataReceived->numeroPresente;

            $updateData['renvoi'] = $dataReceived->renvoi;

            return $updatePointEntree->update($updateData);
        }

        return false;

    }

}
