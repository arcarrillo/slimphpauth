<?php
namespace App\Services;

class GoogleServices{
    private $CLIENT_ID = "998924255142-qjk5flj5hsgnn2un1kq7le21v841mu9v.apps.googleusercontent.com";
    private $CLIENT_SECRET = "wh-vQEWs_vSnsDP79yv8Retg";
    private $REDIRECT_URI = "http://hgmappnovios.com/google-callback";
    private $TOKEN_URI = "https://www.googleapis.com/oauth2/v4/token";
    private $AUTH_URI = "https://accounts.google.com/o/oauth2/v2/auth";
    private $USERINFO_URI = "https://www.googleapis.com/oauth2/v3/userinfo";

    public function generateLoginURL(){
        $parts = [
            "client_id" => $this->CLIENT_ID,
            "redirect_uri"=>$this->REDIRECT_URI,
            "scope"=> "profile email",
            "access_type"=>"online",
            "response_type"=>"code"
        ];

        $joined_parts = http_build_query($parts);
        $result = "$this->AUTH_URI?$joined_parts";
        return $result;
    }

    public function getToken($code){
        $data = [
            "code"=>"$code",
            "client_id"=>$this->CLIENT_ID,
            "client_secret"=>$this->CLIENT_SECRET,
            "redirect_uri"=>$this->REDIRECT_URI,
            "grant_type"=>"authorization_code"
        ];

        $options = array(
            'http' => array(
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($this->TOKEN_URI, false, $context);
        if ($result === FALSE) { /* Handle error */ }
        return json_decode($result);
    }

    public function getProfile($token){
        $options = array(
            'http' => array(
                'header' => 'Authorization: Bearer '.$token,
                'method'  => 'GET'
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($this->USERINFO_URI, false, $context);
        if ($result === FALSE) { /* Handle error */ }

        return json_decode($result);
    }

}
