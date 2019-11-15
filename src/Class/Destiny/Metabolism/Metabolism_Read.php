<?php

Core::class('Destiny/Metabolism/Metabolism');

class Metabolism_Read extends Metabolism {

    public function read() {

        $stmt = Database::prepare("
            SELECT * FROM ".self::$db_t_metabolism." WHERE account_id = :account_id
        ");

        Database::bind($stmt, ['account_id'], [$this->account_id]);
        Database::execute($stmt);
        
        $vals = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->gender = $vals["gender"];
        $this->height = $vals["height"];
        $this->birthdate = $vals["birthdate"];
        $this->pal = $vals["pal"];

    }
  
}