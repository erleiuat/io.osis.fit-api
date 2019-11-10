<?php

Core::class('Account/Account');

class Account_Verify extends Account {

    public function __construct($id) {

        $this->id = $id;

        $stmt = Database::prepare("SELECT mail FROM " . self::$db_t_acc . " WHERE account_id = :account_id");
        Database::bind($stmt, ['account_id'], [$this->id]);
        Database::execute($stmt);

        $vals = $stmt->fetch();
        if (!$vals) throw new ApiException(404, "A1002", "Account not found");

        $this->mail = $vals["mail"];

    }

    public function reset() {

        $stmt = Database::prepare("
            REPLACE INTO ". self::$db_t_acc_verification . " 
            (`account_id`, `mail`, `code`, `verified`) VALUES 
            (:account_id, :mail, NULL, NULL)
        ");

        $stmt2 = Database::prepare("
            UPDATE ". self::$db_t_acc . " SET 
            `status` = 'unverified' WHERE 
            `account_id` = :account_id
        ");

        Database::bind($stmt, 
            ['account_id', 'mail'],
            [$this->id, $this->mail]
        );

        Database::bind($stmt2, ['account_id'], [$this->id]);

        Database::execute($stmt);
        Database::execute($stmt2);

    }

    public function create($phrase) {

        $phrase = password_hash($phrase, ENV_Main::encryption);

        $stmt = Database::prepare("
            REPLACE INTO ". self::$db_t_acc_verification . " 
            (`account_id`, `mail`, `code`, `verified`) VALUES 
            (:account_id, :mail, :code, NULL)
        ");

        Database::bind($stmt, 
            ['account_id', 'mail', 'code'],
            [$this->id, $this->mail, $phrase]
        );

        Database::execute($stmt);

    }

    public function verify($phrase) {

        $stmt = Database::prepare("SELECT code FROM " . self::$db_t_acc_verification . " WHERE account_id = :account_id");
        Database::bind($stmt, ['account_id'], [$this->id]);
        Database::execute($stmt);

        $vals = $stmt->fetch();
        if (!$vals) throw new ApiException(404, "A1004", "Account-Verification not found");
        if (!password_verify($phrase, $vals["code"])) return false;

        $stmt = Database::prepare("
        UPDATE ". self::$db_t_acc_verification . " SET 
        `code` = NULL,
        `verified` = CURRENT_TIMESTAMP
        WHERE `account_id` = :account_id
        ");

        $stmt2 = Database::prepare("
            UPDATE ". self::$db_t_acc . " SET 
            `status` = 'verified' WHERE 
            `account_id` = :account_id
        ");

        Database::bind($stmt, ['account_id'], [$this->id]);
        Database::bind($stmt2, ['account_id'], [$this->id]);

        Database::execute($stmt);
        Database::execute($stmt2);

        return true;

    }

}