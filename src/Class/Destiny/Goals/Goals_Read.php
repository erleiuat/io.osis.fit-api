<?php

Core::class('Destiny/Goals/Goals');

class Goals_Read extends Goals {

    public function read() {

        $stmt = Database::prepare("
            SELECT * FROM ".self::$db_t_goals." WHERE account_id = :account_id
        ");

        Database::bind($stmt, ['account_id'], [$this->account_id]);
        Database::execute($stmt);
        
        $vals = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->weight = $vals["weight"];
        $this->fat = $vals["fat"];
        $this->date = $vals["date"];

    }

}