<?php

Core::class("Auth/Session/Session");

class Session_Delete extends Session {

    public static function delete() {
        
        $stmt1 = Database::prepare("
            DELETE FROM ". self::$db_t_auth_session . " WHERE 
            `account_id` = :account_id AND 
            `auth_token_id` = :auth_token_id
        ");

        Database::bind($stmt1, 
            ['account_id', 'auth_token_id'], 
            [self::$session->issuer, self::$session->token]
        );

        Database::execute($stmt1);

        return true;

    }

    public static function all() {

        $stmt1 = Database::prepare("DELETE FROM " . self::$db_t_auth_session);
        Database::execute($stmt1);

        return true;

    }

    public static function byAccountId($account_id) {

        $stmt1 = Database::prepare("
            DELETE FROM ". self::$db_t_auth_session . " 
            WHERE `account_id` = :account_id
        ");

        Database::bind($stmt1, ['account_id'], [$account_id]);

        Database::execute($stmt1);

        return true;
        
    }
    
}


