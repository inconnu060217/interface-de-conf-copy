<?php

namespace App\Services\profile;
use App\Services\logErrors\ILogErrorService;
class ProfileService implements IProfileService
{
    private ILogErrorService $iLogErrorService;

    private IProfileRepository $iProfileRepository;
    public function __construct(ILogErrorService $iLogErrorService, IProfileRepository $iProfileRepository) {

        $this->iLogErrorService = $iLogErrorService;

        $this->iProfileRepository = $iProfileRepository;

    }

    public function getAllProfile () {
        try {
            $methode = 'Fichier: ProfileService - Mehtode: getAllProfile -';

            $profiles = $this->iProfileRepository->getAllProfile();

            $this->iLogErrorService->ecrireTrace(json_encode($profiles), 'try', $methode, 0);

            return $profiles;

        } catch (\Throwable $th) {
            $catch = 'Fichier: ProfileService - Mehtode: getAllProfile - catch Exception $th: -';

            $this->iLogErrorService->ecrireTrace(json_encode($th->getMessage()), 'catch', $catch, 0);

            return response()->json($th->getMessage());
        }
    }
}
