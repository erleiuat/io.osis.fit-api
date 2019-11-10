<?php

Core::class('Account/Account');

class Account_Create extends Account {

    public function create() {

        $unique = uniqid('', true);
        $time = date('Y_m_d_H_i_s', time());
        $this->id = hash('ripemd160', $time . ':' . $unique);

        $stmt1 = Database::prepare("
            INSERT INTO ". self::$db_t_acc . " 
            (`account_id`, `mail`, `username`, `password`) VALUES 
            (:account_id, :mail, :username, :password);
        ");
        
        Database::bind($stmt1, 
            ['account_id', 'mail', 'username', 'password'], 
            [$this->id, $this->mail, $this->username, $this->password]
        );

        $stmt2 = Database::prepare("
            INSERT INTO ". self::$db_t_acc_detail . " 
            (`account_id`, `firstname`, `lastname`, `birthdate`, `locale`) VALUES 
            (:account_id, :firstname, :lastname, :birthdate, :locale);
        ");
        
        Database::bind($stmt2, 
            ['account_id', 'firstname', 'lastname', 'birthdate', 'locale'], 
            [$this->id, $this->firstname, $this->lastname, $this->birthdate, $this->locale]
        );
    

        Database::execute($stmt1);
        Database::execute($stmt2);


    }

}