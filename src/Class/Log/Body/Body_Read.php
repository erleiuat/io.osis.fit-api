<?php

Core::class('Log/Body/Body');

class Body_Read extends Body {

    public function readById($id) {

        $stmt = Database::prepare("
            SELECT * FROM " . self::$db_t_log_body . " WHERE 
            account_id = :account_id AND
            a_log_body_id = :a_log_body_id
        ");

        Database::bind($stmt, ['account_id', 'a_log_body_id'], [$this->account_id, $id]);
        Database::execute($stmt);

        $val = $stmt->fetch();
        if (!$val) throw new ApiException(404, "E0001", "Entry not found (".get_class($this)."->".__FUNCTION__.")");
        return $val;

    }

    public function read() {

        $stmt = Database::prepare("
            SELECT * FROM " . self::$db_t_log_body . " 
            WHERE account_id = :account_id  
            ORDER BY `stamp` DESC
        ");
        Database::bind($stmt, ['account_id'], [$this->account_id]);

        Database::execute($stmt);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

}