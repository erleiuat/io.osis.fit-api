<?php

Core::class("Auth/Session/Session");

class Session_Update extends Session {

    public function __construct($refresh_jti) {

        parent::__construct(false);

        $this->refresh_token_id = $refresh_jti;

        $stmt = Database::prepare("
            SELECT * FROM ". self::$db_t_auth_session . " 
            WHERE refresh_token_id = :refresh_token_id
        ");

        Database::bind($stmt, 
            ['refresh_token_id'], [$this->refresh_token_id]
        );

        Database::execute($stmt);
        $vals = $stmt->fetch();

        if (!$vals) throw new ApiException(404, "O1109", "Session (RefreshID) not found");

        $this->id = $vals["id"];
        $this->keep = $vals["keep"];
        $this->account_id = $vals["account_id"];
        $this->refresh_phrase = $vals["refresh_phrase"];
        $this->total_refreshes = $vals["total_refreshes"];

    }

    public function update() {

        $device = $this->getDeviceInfo();
        $this->operating_system = $device["operating_system"];
        $this->browser = $device["browser"];

        $auth_jti = uniqid('ajti', true);
        $time = date('Y_m_d_H_i_s', time());
        $this->auth_token_id = hash('ripemd160', $time . ':' . $auth_jti);

        $new_total = $this->total_refreshes + 1;

        $stmt1 = Database::prepare("
            UPDATE ". self::$db_t_auth_session . " SET 
            `latest_refresh` = CURRENT_TIMESTAMP, 
            `total_refreshes` = :new_total,
            `auth_token_id` = :auth_token_id,
            `refresh_phrase` = :refresh_phrase,
            `operating_system` = :operating_system,
            `browser` = :browser
            WHERE `account_id` = :account_id AND `refresh_token_id` = :refresh_token_id
        ");

        Database::bind($stmt1, 
            ['account_id', 'new_total', 'auth_token_id', 'refresh_token_id', 'refresh_phrase', 'operating_system', 'browser'], 
            [$this->account_id, $new_total, $this->auth_token_id, $this->refresh_token_id, $this->refresh_phrase, $this->operating_system, $this->browser]
        );

        Database::execute($stmt1);

        return true;
        
    }
    
}