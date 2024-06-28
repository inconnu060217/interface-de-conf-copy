<?php

namespace App\Services\groups;
use App\Services\connexionsKiamos\IConnexionKiamoService;
use App\Services\logErrors\ILogErrorService;

use Illuminate\Http\JsonResponse;

class GroupService implements IGroupService
{

    private ILogErrorService $ILogErrorService;

    private IGroupRepository $IGroupRepository;

    private IConnexionKiamoService $iConnexionKiamoService;

    public function __construct(

        ILogErrorService $ILogErrorService,

        IGroupRepository $IGroupRepository,

        IConnexionKiamoService $iConnexionKiamoService

        )

    {

        $this->ILogErrorService = $ILogErrorService;

        $this->IGroupRepository= $IGroupRepository;

        $this->iConnexionKiamoService = $iConnexionKiamoService;

    }
    public function getAll (): JsonResponse
    {
        try {

            $methode = 'Fichier: GroupeService - Mehtode: getGroupes -';

            $response = $this->IGroupRepository->getAll();

            $requestUserInformation = $this->iConnexionKiamoService->requestUserInformation();

            $requestUserInformationDecode = json_decode($requestUserInformation["data"], true);

            $groupesCorrespondants = [];

            if($requestUserInformationDecode["profile"] === "kiamo_super_administrateur"){

                $groupesCorrespondants = $response;

            } else {

                foreach ($requestUserInformationDecode["groups"] as $service) {
                    foreach ($response as $groupe) {
                        if (intval($service['id']) === intval($groupe['kid_groupe'])) {
                            $groupesCorrespondants[] = $groupe;
                            break;
                        }
                    }
                }
            }

            $this->ILogErrorService->ecrireTrace(json_encode($groupesCorrespondants), 'try', $methode, 0);

            return response()->json($groupesCorrespondants);

        } catch (\Throwable $th) {

            $catch = 'Fichier: GroupeService - Mehtode: getGroupes - catch Exception $th: -';

            $this->ILogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json( $th->getMessage());
        }
    }
}
