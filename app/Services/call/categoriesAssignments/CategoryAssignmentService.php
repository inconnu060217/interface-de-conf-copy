<?php

namespace App\Services\call\categoriesAssignments;

use App\Services\logErrors\ILogErrorService;

class CategoryAssignmentService implements ICategoryAssignmentService
{
    private $iLogErrorService;

    private CategoryAssignmentRepository $CategoryAssignmentRepository;
    
    public function __construct(
        ILogErrorService $iLogErrorService,
        CategoryAssignmentRepository $CategoryAssignmentRepository
        )
    {
        $this->iLogErrorService = $iLogErrorService;

        $this->CategoryAssignmentRepository = $CategoryAssignmentRepository;

    }
    public function getCategoryAffectation() {
        try {

            $methode = 'Fichier: CategoryAssignmentService - Mehtode: getCategoryAffectation -';

            $response = $this->CategoryAssignmentRepository->getCategoryAffectation();

            $this->iLogErrorService->ecrireTrace(json_encode($response), 'try', $methode, 0);

            return $response;

        } catch (\Throwable $th) {

            $catch = 'Fichier: CategoryAssignmentService - Mehtode: getGategoriesMessages - catch Exception $th: -';


            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json($th->getMessage());
        }
    }

}
