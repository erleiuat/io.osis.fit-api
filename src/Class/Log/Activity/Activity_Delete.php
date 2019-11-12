<?php

Core::class('Log/Activity/Activity_Read');

class Activity_Delete extends Activity_Read {

    public function delete() {

        $stmt = Database::prepare("
            DELETE FROM " . self::$db_t_log_activity . " WHERE 
            account_id = :account_id AND
            a_log_activity_id = :a_log_activity_id
        ");

        Database::bind($stmt, ['account_id', 'a_log_activity_id'], [$this->account_id, $this->a_log_activity_id]);
        Database::execute($stmt);

    }

}