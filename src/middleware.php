<?php
//Security middleware
$app->add(function ($request, $response, $next) {
    $jwttoken = null;

    if ($_COOKIE["token"] != null){
        $jwttoken = $_COOKIE["token"];
    }
    
    if ($jwttoken != null){
        try{
            $service = $this->get("SessionService");
            $jwtService = $this->get("JWTService");
            
            $payload = $jwtService->decodeJWT($jwttoken);
            if ($payload != null){
                $tmp = $service->getSession($payload->t);
                
                if ($tmp !== false){
                    $user["isAuthenticated"] = true;
                    $user["ID"] = $tmp->user->id;
                    $user["Name"] = $tmp->user->name;
                    $user["Surname"] = $tmp->user->surname;
                    $user["Email"] = $tmp->user->email;
                }
            }
        } catch (Exception $excep){
            
        }
    }

    $request = $request->withAttribute('user', $user);
    $response = $next($request, $response);

	return $response;
});