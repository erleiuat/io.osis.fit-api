<?php

Core::class('Log/Body/Body');

class Body_Create extends Body {

    public function create() {

        if ($this->date && $this->time) $stamp = $this->date." ".$this->time;
        else {
            $stamp = date('Y-m-d H:i');
            $this->date = date('Y-m-d', strtotime($stamp));
            $this->time = date('H:i', strtotime($stamp));
        };

        $stmt = Database::prepare("
            INSERT INTO ". self::$db_t_log_body . " 
            (`account_id`, `weight`, `fat`, `stamp`) VALUES 
            (:account_id, :weight, :fat, :stamp)
        ");

        Database::bind($stmt, 
            ['account_id', 'weight', 'fat', 'stamp'], 
            [$this->account_id, $this->weight, $this->fat, $stamp]
        );

        Database::execute($stmt);
        
        $this->a_log_body_id = Database::getID();

    }

}