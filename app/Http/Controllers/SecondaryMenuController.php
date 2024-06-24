<?php

namespace App\Http\Controllers;

use App\Services\secondaryMenus\ISecondaryMenuService;


class SecondaryMenuController extends Controller
{
    private ISecondaryMenuService $ISecondaryMenuService;
    public function __construct(ISecondaryMenuService $ISecondaryMenuService)
    {
        $this->ISecondaryMenuService = $ISecondaryMenuService;
    }

    public function getAll ($media){
        return $this->ISecondaryMenuService->getAll($media);
    }
}

