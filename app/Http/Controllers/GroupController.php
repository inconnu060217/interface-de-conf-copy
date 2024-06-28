<?php

namespace App\Http\Controllers;

use App\Services\groups\IGroupService;

class GroupController extends Controller
{
    private IGroupService $IGroupService;

    public function __construct(
        IGroupService $IGroupService
        )
    {
        $this->IGroupService = $IGroupService;
    }
    public function getAll(){
        return $this->IGroupService->getAll();
    }
}
