<?php

Core::class('Log/Food/Food');

class Food_Create extends Food {

    public function create() {

        if ($this->date && $this->time) $stamp = $this->date." ".$this->time;
        else $stamp = date('Y-m-d H:i');

        $stmt = Database::prepare("
            INSERT INTO ". self::$db_t_log_food . " 
            (`account_id`, `title`, `total_calories`, `total_fat`, `total_protein`, `portion_size`, `stamp`) VALUES 
            (:account_id, :title, :total_calories, :total_fat, :total_protein, :portion_size, :stamp)
        ");

        Database::bind($stmt, 
            ['account_id', 'title', 'total_calories', 'total_fat', 'total_protein', 'portion_size', 'stamp'], 
            [$this->account_id, $this->title, $this->total_calories, $this->total_fat, $this->total_protein, $this->portion_size, $stamp]
        );

        Database::execute($stmt);
        
        $this->a_log_food_id = Database::getID();

    }

}