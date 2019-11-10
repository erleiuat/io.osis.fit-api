<?php

Core::class('Account/Account_Read');

class Account_Update extends Account_Read {

    public function __construct($id, $patch = false) {
        if ($patch) parent::__construct($id, true);
        else {
            parent::__construct(false);
            $this->id = $id;
        }
    }

    public function update() {

        $stmt1 = Database::prepare("
            UPDATE ". self::$db_t_acc . " SET 
            `mail` = :mail,
            `username` = :username 
            WHERE `account_id` = :account_id
        ");
        
        Database::bind($stmt1, 
            ['account_id', 'mail', 'username'],
            [$this->id, $this->mail, $this->username]
        );

        $stmt2 = Database::prepare("
            UPDATE ". self::$db_t_acc_detail . " SET 
            `firstname` = :firstname,
            `lastname` = :lastname, 
            `birthdate` = :birthdate, 
            `locale` = :locale,
            `avatar` = :avatar
            WHERE `account_id` = :account_id
        ");
        
        Database::bind($stmt2, 
            ['account_id', 'firstname', 'lastname', 'birthdate', 'locale', 'avatar'], 
            [$this->id, $this->firstname, $this->lastname, $this->birthdate, $this->locale, $this->avatar]
        );
    
        Database::execute($stmt1);
        Database::execute($stmt2);

    }

    public function setStatus($status) {

        $stmt1 = Database::prepare("
            UPDATE ". self::$db_t_acc . " SET 
            `status` = :status
            WHERE `account_id` = :account_id
        ");
        
        Database::bind($stmt1, 
            ['account_id', 'status'],
            [$this->id, $status]
        );

        Database::execute($stmt1);

    }

    public function setLevel($level) {

        $stmt1 = Database::prepare("
            UPDATE ". self::$db_t_acc . " SET 
            `level` = :level
            WHERE `account_id` = :account_id
        ");
        
        Database::bind($stmt1, 
            ['account_id', 'level'],
            [$this->id, $level]
        );

        Database::execute($stmt1);

    }

}

