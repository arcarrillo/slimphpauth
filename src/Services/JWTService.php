<?php
namespace App\Services;

use Firebase\JWT\JWT as JWT;

final class JWTService{

    private $KEY = "PQ8W95R5fGY3wy6MAuV4BgUWcYqYVNA7ALUcFQCw2YPJmV5ZGSEKptAwSbU4hCvaJrhUFsPTqBJ2eU832zTdXw9RMA34vpRVjUjRegEYgqXK7BD5zw2kqRTDwWjGshear8TznQt8FfHmPQvsjNusAbGuR6FqCA8SYDStcjvcvErSwcthjaVaAwutmWfkw3fvNGteeRgDrkhHqt2BNA3a2ngPtyyCB9P4tRDZ4CdJxJAhErBBpq2gdpKDnu8AtkEF";

    public function generateJWT($payload){
        $now_seconds = time();

        $payload["iss"] = "vps220344.ovh.net";
        $payload["iat"] = $now_seconds;
        $payload["exp"] = $now_seconds+(60*60*24);

        return JWT::encode($payload, $this->KEY);
    }

    public function decodeJWT($token){
        $decoded = JWT::decode($token, $this->KEY, array('HS256'));

        return $decoded;
    }


}