<?php

Core::class('Destiny/Metabolism/Metabolism_Read');

class Metabolism_Patch extends Metabolism_Read {

    public function patch() {

        $stmt = Database::prepare("
            REPLACE INTO ".self::$db_t_metabolism." 
            (`account_id`, `gender`, `height`, `birthdate`, `pal`) VALUES 
            (:account_id, :gender, :height, :birthdate, :pal);
        ");

        Database::bind($stmt, 
            ['account_id', 'gender', 'height', 'birthdate', 'pal'], 
            [$this->account_id, $this->gender, $this->height, $this->birthdate, $this->pal]
        );
        
        Database::execute($stmt);

    }
  
}