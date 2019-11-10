<?php

Core::class("Auth/Session/Session");

class Session_Create extends Session {

    public function __construct($acc_id, $phrase, $keep = false) {

        parent::__construct($acc_id);
        $this->setPhrase($phrase);
        $this->keep = $keep;

    }

    public function create() {

        $device = $this->getDeviceInfo();
        $this->operating_system = $device["operating_system"];
        $this->browser = $device["browser"];

        $auth_jti = uniqid('ajti', true);
        $refresh_jti = uniqid('rjti', true);
        $time = date('Y_m_d_H_i_s', time());
        $this->auth_token_id = hash('ripemd160', $time . ':' . $auth_jti);
        $this->refresh_token_id = hash('ripemd160', $time . ':' . $refresh_jti);

        $stmt1 = Database::prepare("
            INSERT INTO ". self::$db_t_auth_session . " 
            (`account_id`,`keep`, `auth_token_id`, `refresh_token_id`, `refresh_phrase`, `operating_system`, `browser`) VALUES 
            (:account_id, :keep, :auth_token_id, :refresh_token_id, :refresh_phrase, :operating_system, :browser);
        ");

        $stmt2 = Database::prepare("
            SELECT * FROM ". self::$db_t_auth . " 
            WHERE account_id = :account_id
        ");

        $stmt3 = Database::prepare("
            UPDATE ". self::$db_t_auth . " SET 
            `latest_login` = CURRENT_TIMESTAMP, 
            `total_logins` = :new_total
            WHERE `account_id` = :account_id;
        ");
        
        Database::bind($stmt1, 
            ['account_id', 'keep', 'auth_token_id', 'refresh_token_id', 'refresh_phrase', 'operating_system', 'browser'], 
            [$this->account_id, $this->keep, $this->auth_token_id, $this->refresh_token_id, $this->refresh_phrase, $this->operating_system, $this->browser]
        );

        Database::bind($stmt2, 
            ['account_id'],
            [$this->account_id]
        );

        Database::execute($stmt1);
        Database::execute($stmt2);

        $vals = $stmt2->fetch();
        if (!$vals) throw new ApiException(404, "O1106", "Auth-Account not found");

        $new_total = $vals['total_logins'] + 1;
        Database::bind($stmt3, 
            ['account_id', 'new_total'],
            [$this->account_id, $new_total]
        );

        Database::execute($stmt3);

        return true;

    }

    
}