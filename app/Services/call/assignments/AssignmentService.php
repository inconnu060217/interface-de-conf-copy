<?php

namespace App\Services\call\assignments;
use App\Services\logErrors\ILogErrorService;
use Illuminate\Http\JsonResponse;

class AssignmentService implements IAssignmentService
{
    private ILogErrorService $iLogErrorService;

    private IAssignmentRepository $IAssignmentRepository;

    public function __construct(

        ILogErrorService $iLogErrorService,

        IAssignmentRepository $IAssignmentRepository,

        ) {

        $this->iLogErrorService = $iLogErrorService;

        $this->IAssignmentRepository = $IAssignmentRepository;

    }
    public function getAll ($kidGroupe): JsonResponse
    {
        try {
            $methode = 'Fichier: AssignmentService - Mehtode: getAll -';

            $response = $this->IAssignmentRepository->getAll($kidGroupe);

            $this->iLogErrorService->ecrireTrace(json_encode($response), 'try', $methode, 0);

            return response()->json($response);

        } catch (\Throwable $th) {

            $catch = 'Fichier: AssignmentService - Mehtode: getAll - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json($th->getMessage());
        }
    }

    public function getForTypeDeterrence ($kidGroupe)
    {
        try {
            $methode = 'Fichier: getForTypeDeterrence - Mehtode: getAll -';

            $response = $this->IAssignmentRepository->getForTypeDeterrence($kidGroupe);

            $this->iLogErrorService->ecrireTrace(json_encode($response), 'try', $methode, 0);

            return response()->json($response);

        } catch (\Throwable $th) {

            $catch = 'Fichier: AssignmentService - Mehtode: getForTypeDeterrence - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json($th->getMessage());
        }
    }

    public function getAllExceptionalClosure($kidGroupe): JsonResponse
    {
        try {

            $methode = 'Fichier: AssignmentService - Mehtode: getAllFermetureExceptionnelle -';

            $response = $this->IAssignmentRepository->getAllExceptionalClosure($kidGroupe);

            $this->iLogErrorService->ecrireTrace(json_encode($response), 'try', $methode, 0);
            return response()->json($response);

        } catch (\Throwable $th) {

            $catch = 'Fichier: AssignmentService - Mehtode: getAllFermetureExceptionnelle - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json($th->getMessage());
        }
    }
    public function getForBankMessage(): array
    {
        try {
            $methode = 'Fichier: AssignmentService - Mehtode: getForBanqueMessage -';

            $response = $this->IAssignmentRepository->getForBanKMessage();

            $this->iLogErrorService->ecrireTrace(json_encode($response), 'try', $methode, 0);

            return $response;

        } catch (\Throwable $th) {

            $catch = 'Fichier: AssignmentService - Mehtode: getForBankMessage - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return [$th->getMessage()];
        }
    }
    public function getForAlls ($kidGroupe){
        try {
            $methode = 'Fichier: AssignmentService - Mehtode: getForAll -';

            $response = $this->IAssignmentRepository->getForAlls($kidGroupe);

            $this->iLogErrorService->ecrireTrace(json_encode($response), 'try', $methode, 0);

            return response()->json($response);

        } catch (\Throwable $th) {

            $catch = 'Fichier: AssignmentService - Mehtode: getForAll - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json($th->getMessage());
        }
    }
    public function updateExceptionalClosure($dataReceived): JsonResponse
    {
        try {

            $methode = 'Fichier: AssignmentService - Mehtode: updateMessageFermetureExceptionnelle -';

            $response = $this->IAssignmentRepository->updateExceptionalClosure($dataReceived);

            $this->iLogErrorService->ecrireTrace(json_encode($dataReceived), 'try', $methode, 0);

            if($response === true) {

                return response()->json(['message' => 'Le message a bien été mis à jour.']);

            }
            else {

                return response()->json(['status' => 400, 'message' => "Une erreur s'est produite lors de la requête. Veuillez ressayer."]);

            }

        } catch (\Throwable $th) {

            $catch = 'Fichier: MessageService - Mehtode: updateMessageFermetureExceptionnelle - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json($th->getMessage());
        }
    }

    public function update ($dataReceived) {
        try {

            $methode = 'Fichier: AssignmentService - Mehtode: update -';


            $this->iLogErrorService->ecrireTrace(json_encode($dataReceived), 'try', $methode, 0);

            $response =  $this->IAssignmentRepository->update($dataReceived);

            if($response === true) {

                return response()->json(['message' => 'Le message a bien été mis à jour.']);

            }
            else {

                return response()->json(['status' => 400, 'message' => "Une erreur s'est produite lors de la requête. Veuillez ressayer."]);

            }

        } catch (\Throwable $th) {

            $catch = 'Fichier: AssignmentService - Mehtode: update - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json($th->getMessage());
        }
    }
}
