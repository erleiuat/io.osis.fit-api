<?php

Core::class("Auth/Auth");

class Auth_Create extends Auth {

    public function create() {

        $stmt = Database::prepare("
            INSERT INTO ". self::$db_t_auth . " 
            (`account_id`) VALUES (:account_id);
        ");
        
        Database::bind($stmt, 
            ['account_id'], [$this->account_id]
        );

        Database::execute($stmt);

    } 

}