<?php

namespace App\Controllers;

use Doctrine\ORM\EntityManager;
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;
use Slim\Views\PhpRenderer as PhpRenderer;


final class PagesController{

    private $em;
    private $renderer;
    private $gservices;

    private $userService;
    private $sessionService;
    private $jwtService;

    public function __construct(EntityManager $em, PhpRenderer $renderer, 
        \App\Services\GoogleServices $gServices, 
        \App\Services\UserService $userService,
        \App\Services\SessionService $sessionService,
        \App\Services\JWTService $jwtService)
    {
        $this->em = $em;
        $this->renderer = $renderer;
        $this->gservices = $gServices;

        $this->userService = $userService;
        $this->sessionService = $sessionService;
        $this->jwtService = $jwtService;
    }

    public function index(Request $request, Response $response, array $args) {
        $user = $request->getAttribute('user');

        if (!$user["isAuthenticated"]){
            $link = $this->gservices->generateLoginURL();
            return $this->renderer->render($response, 'index.phtml', array(
                "link" => $link
            ));
        } else {
            return $response->withRedirect("/profile");
        }
    }

    public function google_callback(Request $request, Response $response, array $args){
        $code = $request->getQueryParam("code");

        if ($code != null){
            $token = $this->gservices->getToken($code);
            if ($token !== false){
                $userinfo = $this->gservices->getProfile($token->access_token);
                if ($userinfo !== false){
                    $user = $this->userService->getUserByEmail($userinfo->email);

                    if ($user == null)
                        $user = $this->userService->registerUser($userinfo->given_name, $userinfo->family_name, $userinfo->email);

                    if ($user !== false){
                        return $this->generateSession($response, $user, $token);
                    }
                }
            }
        }

        return $response->withRedirect("/");
    }

    private function generateSession(Response $response, $user, $tokenData){
        $sessionGUID = $this->sessionService->newSession($user, $tokenData->access_token, $tokenData->refresh_token);

        if ($sessionGUID !== false){
            $jwtToken = $this->jwtService->generateJWT(["t"=>$sessionGUID]);
            \setcookie("token", $jwtToken, time()+(60*60*24));
            return $response->withRedirect("/profile");
        }

        return $response->withRedirect("/");
    }

    public function profile(Request $request, Response $response, array $args){
        $user = $request->getAttribute('user');

        if ($user["isAuthenticated"] == true){
            return $this->renderer->render($response, 'profile.phtml', ["user"=>$user]);
        } else 
            return $response->withRedirect("/");
    }

    public function logout(Request $request, Response $response, array $args) {
        \setcookie("token", null, -1);
        return $response->withRedirect("/");
    }

}