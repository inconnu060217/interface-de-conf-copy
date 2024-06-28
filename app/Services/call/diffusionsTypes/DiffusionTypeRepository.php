<?php

namespace App\Services\call\diffusionsTypes;
use App\Models\TypeOfBroadcastModel;

class DiffusionTypeRepository implements IDiffusionTypeRepository
{
    private TypeOfBroadcastModel $typeOfBroadcastModel;

    public function __construct(TypeOfBroadcastModel $typeOfBroadcastModel)
    {

        $this->typeOfBroadcastModel = $typeOfBroadcastModel;

    }

    public function getAll(){

        return $this->typeOfBroadcastModel::all()->toArray();

    }
}
