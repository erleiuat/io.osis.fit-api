<?php

Core::class('Log/Activity/Activity');

class Activity_Create extends Activity {

    public function create() {

        if ($this->date && $this->time) $stamp = $this->date." ".$this->time;
        else {
            $stamp = date('Y-m-d H:i');
            $this->date = date('Y-m-d', strtotime($stamp));
            $this->time = date('H:i', strtotime($stamp));
        };

        $stmt = Database::prepare("
            INSERT INTO ". self::$db_t_log_activity . " 
            (`account_id`, `title`, `burned_calories`, `type`, `stamp`) VALUES 
            (:account_id, :title, :burned_calories, :type, :stamp)
        ");

        Database::bind($stmt, 
            ['account_id', 'title', 'burned_calories', 'type', 'stamp'], 
            [$this->account_id, $this->title, $this->burned_calories, $this->type, $stamp]
        );

        Database::execute($stmt);
        
        $this->a_log_activity_id = Database::getID();

    }

}