<?php

Core::class("Auth/Session/Session");

class Session_Password extends Session {

    public static function createReset($account_id, $phrase) {

        $phrase = password_hash($phrase, ENV_Main::encryption);

        $stmt = Database::prepare("
            REPLACE INTO ".self::$db_t_auth_pass." (`account_id`, `created`, `phrase`) VALUES 
            (:account_id, CURRENT_TIMESTAMP, :phrase);
        ");

        Database::bind($stmt, 
            ['account_id', 'phrase'], 
            [$account_id, $phrase]
        );

        Database::execute($stmt);

    }

    public static function verifyReset($account_id, $phrase) {

        $stmt = Database::prepare("
            SELECT * FROM ".self::$db_t_auth_pass." WHERE
            account_id = :account_id
        ");

        Database::bind($stmt, ['account_id'], [$account_id]);
        Database::execute($stmt);

        $vals = $stmt->fetch();
        if (!$vals) throw new ApiException(404, "O1145", "Reset entry not found");

        if (password_verify($phrase, $vals["phrase"])) {

            $stmt2 = Database::prepare("
                DELETE FROM ".self::$db_t_auth_pass." WHERE 
                `account_id` = :account_id
            ");

            Database::bind($stmt2, ['account_id'], [$account_id]);
            Database::execute($stmt2);

            return true;

        }

        return false;

    }
    
}