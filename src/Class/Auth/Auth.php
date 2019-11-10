<?php

Core::library("JWT");
Core::environment("ENV_Auth");

class Auth {

    protected static $db_t_auth = "authentication";
    protected static $db_t_auth_session = "auth_session";
    protected static $db_t_auth_pass = "auth_passreset";
    protected static $db_v_auth_deep = "v_auth_deep";

    public $account_id;
    public static $session;

    public function __construct($account_id = false) {
        if ($account_id) $this->account_id = $account_id;
    }

    public static function decodeToken($token, $secret, $alg = ENV_Auth::token_algorithm) {
        try {

            $tkn = JWT::decode($token, $secret, $alg);
            return $tkn;

        } catch (\Exception $e) {

            $exception_type = get_class($e);

            if ($exception_type === "ExpiredException")
                throw new ApiException(403, "O1171", "Expired Token");

            else if ($exception_type === "SignatureInvalidException") {
                Log::setLevel("warn");
                Log::addInformation("!JWT=Invalid Signature");
                throw new ApiException(403, "O1172", "Invalid Token Signature");
            }

            Log::setLevel("error");
            Log::addInformation("JWTException=" . $exception_type);
            throw new ApiException(500, "O1173", "Token decode error");

        }
    }

    /** $deepCheck: Check session against database */
    public static function getSession($required = true, $deepCheck = false) {

        if (!isset(getallheaders()['Authorization'])) {
            if (!$required) return;
            throw new ApiException(403, "O1187", "Token missing");
        } 
        
        list($type, $token) = explode(" ", getallheaders()['Authorization'], 2);
        $content = self::decodeToken($token, ENV_Auth::auth_token_key);

        Log::identifier("AUTHJTI:" . $content->jti);
        Log::identifier("ISSUER:" . $content->iss);
        Log::identifier("MAIL:" . $content->data->account->mail);

        if ($deepCheck) {
            
            $stmt = Database::prepare("
                SELECT * FROM ". self::$db_v_auth_deep . " 
                WHERE auth_token_id = :auth_token_id
            ");

            Database::bind($stmt, ['auth_token_id'], [$content->jti]);
            Database::execute($stmt);
            $vals = $stmt->fetch();

            if (!$vals) {
                if (!$required) return;
                throw new ApiException(403, "O1188", "Session not found (DeepCheck)");
            }

            if ($vals['account_id'] !== $content->iss || $vals['account_id'] !== $content->data->account->id)
                throw new ApiException(403, "O1189", "Session issuer mismatch (DeepCheck)");

            $content->data->account->level = $vals['level'];
            $content->data->account->status = $vals['status'];

        }

        if ($content->data->account->status === 'locked')
            throw new ApiException(403, "O1104", "Account locked");

        else if ($content->data->account->status === 'deleted')
            throw new ApiException(403, "O1105", "Account deleted");

        self::$session = (object) [
            "issuer" => $content->iss,
            "subject" => $content->sub,
            "token" => $content->jti,
            "account" => (object) [
                "id" => $content->data->account->id,
                "mail" => $content->data->account->mail,
                "username" => $content->data->account->username,
                "level" => $content->data->account->level,
                "status" => $content->data->account->status
            ]
        ];

    }

    /** level "owner" to allow if session-account === $account_id */
    public static function permit($account_id, ...$levels) {

        if (self::$session->issuer !== $account_id) {
            if (in_array(self::$session->account->level, $levels)) return true;
            throw new ApiException(403, "O1141", "Permissions denied");
        } else if (in_array("owner", $levels)) return true;
        
        throw new ApiException(403, "O1142", "Permissions denied");
        
    }

}