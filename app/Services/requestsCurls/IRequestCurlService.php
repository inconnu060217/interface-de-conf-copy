<?php

namespace App\Services\requestsCurls;

interface IRequestCurlService
{
    public function getParametreEnvironnement();

    public function requestCurlWsUserInformation($cookieUserAuthKiamo);

    public function requestCurlGeneralNotCookie($endPoint, $method);

    public function requestCurlTextToSpeechFile($endPoint, $method, $postBody);

    
}