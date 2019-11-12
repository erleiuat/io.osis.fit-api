<?php

Core::class('Log/Body/Body_Read');

class Body_Delete extends Body_Read {

    public function delete() {

        $stmt = Database::prepare("
            DELETE FROM " . self::$db_t_log_body . " WHERE 
            account_id = :account_id AND
            a_log_body_id = :a_log_body_id
        ");

        Database::bind($stmt, ['account_id', 'a_log_body_id'], [$this->account_id, $this->a_log_body_id]);
        Database::execute($stmt);

    }

}