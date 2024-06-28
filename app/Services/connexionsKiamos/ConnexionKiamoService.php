<?php

namespace App\Services\connexionsKiamos;
use App\Services\profile\IProfileService;
use Illuminate\Http\Request;
use App\Services\requestsCurls\IRequestCurlService;


class ConnexionKiamoService implements IConnexionKiamoService
{
    private Request $request;

    private IRequestCurlService $iRequestCurlService;

    private IProfileService $iProfileService;

    public function __construct(

        Request $request,

        IRequestCurlService $iRequestCurlService,

        IProfileService $iProfileService

        )
    {

        $this->request = $request;

        $this->iRequestCurlService = $iRequestCurlService;

        $this->iProfileService = $iProfileService;

    }

    // Receuperation du cookie PHPSESSID depuis kiamo
    public function getCookieUserKiamo(){

        $cookieAuth = "";

        $Cookie = explode(";", $this->request->header('cookie'));

        for ($i = 0; $i < count($Cookie); $i++) {

            if (str_contains($Cookie[$i], 'PHPSESSID')) {

                $cookieAuth = explode("=", $Cookie[$i])[1];

                break;

            }

        }

        return $cookieAuth;
    }

    // recuperation de la valeur de cookie passe en parametre depuis le front
    public function getCookieFromBack($cookieToFront){

        $cookie = "";

        $Cookie = explode(";", $this->request->header('cookie'));

        for ($i = 0; $i < count($Cookie); $i++) {

            if (str_contains($Cookie[$i], $cookieToFront)) {

                $cookie = explode("=", $Cookie[$i])[1];

                break;

            }

        }

        return $cookie;

    }

    //Vérifie si un utilisateur est connecté
    public function checkUserAuth(){

        $cookieAuth = $this->getCookieUserKiamo();

        $profile = $this->iProfileService->getAllProfile();

        if (empty($cookieAuth)) {

            return response()->json(['isAuthorized' => false]);

        } else {

            $user = $this->iRequestCurlService->requestCurlWsUserInformation($cookieAuth);


            if (!$user || $user["code"] == 400) {

                return false;

            } else {

                $user = json_decode($user["data"], true);

                if (!in_array($user["profile"], array_column($profile, 'kname'))) {

                    return false;

                }

                $response = response()->json(['isAuthorized' => true]);

                return $response;

            }

        }

    }

    function UserInformation($cookie){

        return $this->iRequestCurlService->requestCurlWsUserInformation($cookie);

    }

    // récuperation des informations de l'utilisateur connecter
    function requestUserInformation(){

        $cookieAuth = $this->getCookieUserKiamo();

        if (!$cookieAuth) return false;

        return $this->iRequestCurlService->requestCurlWsUserInformation($cookieAuth);
    }

     // recupertion d'un quelconque cookie
    public function getCookieFromBackId($anyCookie) {

        // a decommenter pour l'integration avec kiamo
        if (!empty($this->getCookieFromBack("PHPSESSID")) && !empty($this->getCookieFromBack($anyCookie))) {

            return $this->getCookieFromBack($anyCookie);

        } else {

            return null;

        }
    }

    // recupertion de l'id de la  caisse actuellement stoker dans le cookie
    public function getCurrentGroupId() {
        // a decommenter pour l'integration avec kiamo
        if (!empty($this->getCookieFromBack("PHPSESSID")) && !empty($this->getCookieFromBack("id_groupe"))) {

            return $this->getCookieFromBack("id_groupe");

        } else {

            return null;

        }
    }

}
