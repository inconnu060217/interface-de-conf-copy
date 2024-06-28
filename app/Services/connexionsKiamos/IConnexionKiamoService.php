<?php

namespace App\Services\connexionsKiamos;

interface IConnexionKiamoService
{
    public function getCookieUserKiamo();

    public function getCookieFromBack($cookieToFront);

    public function checkUserAuth();

    public function getCurrentGroupId();

    public function requestUserInformation();

    public function getCookieFromBackId($anyCookie);

    public function UserInformation($cookie);

}
