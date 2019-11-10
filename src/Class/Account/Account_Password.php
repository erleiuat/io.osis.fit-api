<?php

Core::class('Account/Account');

class Account_Password extends Account {

    public function __construct($id) {
        $this->id = $id;
    }

    public function changePassword() {

        $stmt1 = Database::prepare("
            UPDATE ". self::$db_t_acc . " SET 
            `password` = :password
            WHERE `account_id` = :account_id
        ");
        
        Database::bind($stmt1, 
            ['account_id', 'password'],
            [$this->id, $this->password]
        );
    
        Database::execute($stmt1);

    }

}

