<?php

namespace App\Services\call\entryPointTypes;
use App\Models\EntryPointTypeModel;

class EntryPointTypeRepository implements IEntryPointTypeRepository
{
    private EntryPointTypeModel $typePointEntreeModel;

    public function __construct(EntryPointTypeModel $typePointEntreeModel)
    {

        $this->typePointEntreeModel = $typePointEntreeModel;

    }
    public function getAll(){

        return $this->typePointEntreeModel::all()->toArray();

    }
}
