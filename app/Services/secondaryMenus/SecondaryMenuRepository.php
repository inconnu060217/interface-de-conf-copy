<?php

namespace App\Services\secondaryMenus;
use App\Models\SecondaryMenuModel;
class SecondaryMenuRepository implements ISecondaryMenuRepository
{
    public function getAll ($media)
    {

        return SecondaryMenuModel::where("type", "=", $media)

            ->where("activation", "!=", 0)

            ->get()

            ->toArray();

    }
}
