<?php

Core::class("Auth/Auth");

class Session extends Auth {

    public $id;
    
    public $keep;
    public $auth_token_id;
    public $refresh_token_id;
    
    public $operating_system;
    public $browser;
    
    public $created;
    public $latest_refresh;
    public $total_refreshes;

    protected $refresh_phrase;


    public function setPhrase($phrase) {
        $this->refresh_phrase = password_hash(ENV_Auth::refresh_phrase . $phrase, ENV_Main::encryption);
        return $this;
    }

    public function checkPhrase($phrase) {
        if (password_verify(ENV_Auth::refresh_phrase . $phrase, $this->refresh_phrase)) return true;
        return false;
    }

    public function getAuthToken($data = null) {

        $now = time();
        $tokenContent = [
            "iss" => $this->account_id, // Issuer (The user authenticating)
            "sub" => ENV_Auth::auth_token_subject, // Subject (for example: what the token is meant for)
            "iat" => $now, // Issued at (When the token got generated)
            "nbf" => $now, // Not before (When the token can be used the first time)
            "exp" => $now + ENV_Auth::auth_token_lifetime, // Expires (Until when the token can be used)
            "jti" => $this->auth_token_id, // JWT token ID (unique ID of the token)
            "data" => $data // Contents of the token
        ];

        return JWT::encode($tokenContent, ENV_Auth::auth_token_key);
        
    }

    public function getRefreshToken($data = null) {

        $now = time();
        $tokenContent = [
            "iss" => $this->account_id, // Issuer (The user authenticating)
            "sub" => ENV_Auth::refresh_token_subject, // Subject (for example: what the token is meant for)
            "iat" => $now, // Issued at (When the token got generated)
            "nbf" => $now, // Not before (When the token can be used the first time)
            "exp" => $now + ENV_Auth::refresh_token_lifetime, // Expires (Until when the token can be used)
            "jti" => $this->refresh_token_id, // JWT token ID (unique ID of the token)
            "data" => $data // Contents of the token
        ];

        return JWT::encode($tokenContent, ENV_Auth::refresh_token_key);

    }

    public static function getDeviceInfo() {

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $os_platform = "Unknown OS Platform";
        $browser = "Unknown Browser";

        foreach (ENV_Auth::operating_systems as $regex => $value) {
            if (preg_match($regex, $user_agent)) $os_platform = $value;
        }

        foreach (ENV_Auth::browsers as $regex => $value) {
            if (preg_match($regex, $user_agent)) $browser = $value;
        }

        return [
            "operating_system" => $os_platform,
            "browser" => $browser
        ];

    }

}