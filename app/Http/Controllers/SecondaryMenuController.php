<?php

namespace App\Http\Controllers;

use App\Services\secondaryMenus\ISecondaryMenuService;
use Illuminate\Http\Request;

class SecondaryMenuController extends Controller
{
    private ISecondaryMenuService $ISecondaryMenuService;
    private Request $request;
    public function __construct(ISecondaryMenuService $ISecondaryMenuService, Request $request)
    {
        $this->ISecondaryMenuService = $ISecondaryMenuService;
        $this->request = $request;
    }

    public function getAll (){
        $media = $this->request->query('media');
        return $this->ISecondaryMenuService->getAll($media);
    }
}

