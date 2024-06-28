<?php

namespace App\Services\call\entryPoints;
use App\Services\webService\queueCallWebServices\IQueueCallWebServiceService;
use Illuminate\Http\Request;
use App\Services\logErrors\ILogErrorService;
use App\Services\webService\svisWebServices\ISviWebServiceService;

class EntryPointService implements IEntryPointService
{
    private Request $request;

    private ILogErrorService $ILogErrorService;

    private IEntryPointRepository $IEntryPointRepository;

    private ISviWebServiceService $iSviWebServiceService;

    private IQueueCallWebServiceService $IQueueCallWebServiceService;

    public function __construct(
        Request                $request,
        ILogErrorService       $iLogErrorService,
        IEntryPointRepository  $IEntryPointRepository,
        ISviWebServiceService  $iSviWebServiceService,
        IQueueCallWebServiceService $IQueueCallWebServiceService
        )
    {
        $this->request = $request;

        $this->ILogErrorService = $iLogErrorService;

        $this->IEntryPointRepository = $IEntryPointRepository;

        $this->iSviWebServiceService = $iSviWebServiceService;

        $this->IQueueCallWebServiceService = $IQueueCallWebServiceService;

    }

    public function findIdCallbackSvi($data, $item2) {

        foreach ($data as $item1) {

            if (intval($item2["kid_svi"]) == intval($item1->id)) {

                return $item1->name;

            }

        }

        return false;

    }

    public function findIdCallbackQueueCall($data, $item2) {

        foreach ($data as $item1) {

            if (intval($item2["kid_file_attente"]) === intval($item1->ServiceId)) {

                return $item1->ServiceName;

            }

        }

        return false;

    }
    public function getAll ($idGroupe, $kidGroupe)
    {
        try {

            $methode = 'Fichier: EntryPointService - Mehtode: getAll -';

            $entryPoints = $this->IEntryPointRepository->getAll($idGroupe);

            $svis = $this->iSviWebServiceService->getAll($kidGroupe);

            $queueCalls = $this->IQueueCallWebServiceService->getAll($kidGroupe);

            if(is_array($entryPoints) && !empty($entryPoints) && is_array($svis) && !empty($svis) && is_array($queueCalls) && !empty($queueCalls)) {

                foreach ($entryPoints as &$item2) {

                    if($item2["nom"] === "SVI"){

                        $item2["routage"] = $this->findIdCallbackSvi($svis, $item2);

                    }
                    else if($item2["nom"] === "File d'attente"){

                        $item2["routage"] = $this->findIdCallbackQueueCall($queueCalls, $item2);

                    }
                    else {

                        $item2["routage"] = $item2["renvoi"];

                    }

                    $item2["name_svi"] = $this->findIdCallbackSvi($svis, $item2);

                    $item2["name_file_attente"] = $this->findIdCallbackQueueCall($queueCalls, $item2);

                }

            }
            $this->ILogErrorService->ecrireTrace(json_encode($entryPoints), 'try', $methode, 0);

            return response()->json($entryPoints);

        } catch (\Throwable $th) {

            $catch = 'Fichier: EntryPointService - Mehtode: getAll - catch Exception $th: -';

            $this->ILogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json($th->getMessage());
        }
    }

    public function update ($dataReceived)
    {
        try {

            $methode = 'Fichier: EntryPointService - Mehtode: update -';

             $this->ILogErrorService->ecrireTrace(json_encode($dataReceived), 'try', $methode, 0);

            $response = $this->IEntryPointRepository->update($dataReceived);

            if($response === true) {

                return response()->json(['message' => 'Le Point entrée a bien été mise à jour.']);

            }

            else {

                return response()->json(['status' => 400, 'message' => "Une erreur s'est produite lors de la requête. Veuillez ressayer."]);

            }


        } catch (\Throwable $th) {

            $catch = 'Fichier: EntryPointService - Mehtode: update - catch Exception $th: -';

            $this->ILogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json($th->getMessage());
        }
    }
}
