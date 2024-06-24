<?php

namespace App\Services\mainMenus;

use App\Models\MainMenuModel;

class MainMenuRepository implements IMainMenuRepository
{
    public function getAll (){

        return MainMenuModel::all()->toArray();

    }
}
