<?php

namespace App\Services\call\plannings;
use App\Models\ScheduleModel;

class PlanningRepository implements IPlanningRepository
{
    private ScheduleModel $schedule;

    public function __construct(ScheduleModel $scheduleModel)
    {
        $this->schedule = $scheduleModel;
    }
    public function getAll($id_groupe, $id_category){

        return $this->schedule::join("message","planning.id_message","=","message.id_message")

        ->where('planning.id_groupe', intval($id_groupe))

        ->where("planning.id_categorie_message", intval($id_category))

        ->get()

        ->toArray();
    }

    public function getForBankMessage() {

        return $this->schedule::select("planning.*")

            ->get()

            ->toArray();

    }

    public function getAllWebService($id_groupe) {

        return $this->schedule::where('planning.id_groupe', $id_groupe)

        ->get()

        ->toArray();

    }
    public function update($dataReceived){

        $updatePlanning = $this->schedule::where('id_planning', '=', intval($dataReceived->id))->first();

        $updatePlanning->id_message = intval($dataReceived->idMessage);

        $updatePlanning->save();

    }

}
