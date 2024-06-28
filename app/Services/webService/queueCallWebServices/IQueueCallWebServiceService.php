<?php

namespace App\Services\webService\queueCallWebServices;

interface IQueueCallWebServiceService
{

    public function getAll ($kidGroupe);

    public function updateQueuePriorite ($value, $kidFileAttente);

    public function updateQueueDureeDissuasionTempsReels ($value, $kidFileAttente);

    public function getPostCallTime($kidQueueCall);
    public function updatePostCallTime($kidQueueCall, $PostCallTime);

    public function callbackAnnonceTime($kidQueueCall,$oldCallbackAnnonceTime, $newCallbackAnnonceTime);

}
