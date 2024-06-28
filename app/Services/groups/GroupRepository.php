<?php

namespace App\Services\groups;
use App\Models\GroupModel;

class GroupRepository implements IGroupRepository
{
    private GroupModel $groupModel;

    public function __construct(GroupModel $groupModel)
    {

        $this->groupModel = $groupModel;

    }
    public function getAll(){

        return $this->groupModel::where('nom', '!=', 'ALL')->get()->toArray();

    }
}
