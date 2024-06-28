<?php

namespace App\Http\Controllers;

use App\Services\call\entryPointTypes\IEntryPointTypeService;

class EntryPointTypeController extends Controller
{
    private IEntryPointTypeService $IEntryPointTypeService;

    public function __construct(IEntryPointTypeService $IEntryPointTypeService)
    {
        $this->IEntryPointTypeService = $IEntryPointTypeService;
    }
    public function getAll()
    {
        return $this->IEntryPointTypeService->getAll();
    }
}
