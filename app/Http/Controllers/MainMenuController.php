<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\mainMenus\IMainMenuService;



class MainMenuController extends Controller
{
    private IMainMenuService $IMainMenuService;
    public function __construct(IMainMenuService $IMainMenuService)
    {
        $this->IMainMenuService = $IMainMenuService;
    }

    public function getAll (){
        return $this->IMainMenuService->getAll();
    }

}
