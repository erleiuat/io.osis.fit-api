<?php

Core::class('Destiny/Goals/Goals_Read');

class Goals_Patch extends Goals_Read {
    
    public function patch() {

        $stmt = Database::prepare("
            REPLACE INTO ".self::$db_t_goals." 
            (`account_id`, `weight`, `fat`, `date`) VALUES 
            (:account_id, :weight, :fat, :date);
        ");

        Database::bind($stmt, 
            ['account_id', 'weight', 'fat', 'date'], 
            [$this->account_id, $this->weight, $this->fat, $this->date]
        );
        
        Database::execute($stmt);

    }

}