<?php

Core::class('Log/Food/Food_Read');

class Food_Delete extends Food_Read {

    public function delete() {

        $stmt = Database::prepare("
            DELETE FROM " . self::$db_t_log_food . " WHERE 
            account_id = :account_id AND
            a_log_food_id = :a_log_food_id
        ");

        Database::bind($stmt, ['account_id', 'a_log_food_id'], [$this->account_id, $this->a_log_food_id]);
        Database::execute($stmt);

    }

}