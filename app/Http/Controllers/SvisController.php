<?php

namespace App\Http\Controllers;

use App\Services\webService\svisWebServices\ISviWebServiceService;
use Illuminate\Http\Request;

class SvisController extends Controller
{
    private ISviWebServiceService $ISviWebServiceService;
    private Request $request;

    public function __construct(ISviWebServiceService $ISviWebServiceService, Request $request,)
    {
        $this->ISviWebServiceService = $ISviWebServiceService;
        $this->request = $request;
    }
    public function getAll()
    {
        $kidGroupe = $this->request->query('kidGroupe');
        return $this->ISviWebServiceService->getAll($kidGroupe);
    }
}
