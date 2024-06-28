<?php

namespace App\Services\call\overflowTypes;
use App\Models\TypeOfOverflowModel;

class OverflowTypeRepository implements IOverflowTypeRepository
{
    private TypeOfOverflowModel $typeOfOverflowModel;

    public function __construct(TypeOfOverflowModel $typeOfOverflowModel)
    {
        $this->typeOfOverflowModel = $typeOfOverflowModel;
    }
    public function getAll(){

        return $this->typeOfOverflowModel::all()->toArray();
    }
}
