<?php

Core::class('Template/Template');

class Template_Read extends Template {

    public function readById($id) {

        $stmt = Database::prepare("
            SELECT * FROM " . self::$db_v_template . " WHERE 
            account_id = :account_id AND
            a_template_id = :a_template_id
        ");

        Database::bind($stmt, ['account_id', 'a_template_id'], [$this->account_id, $id]);
        Database::execute($stmt);

        $val = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$val) throw new ApiException(404, "E0001", "Entry not found (".get_class($this)."->".__FUNCTION__.")");
        return $val;

    }

    public function read() {

        $stmt = Database::prepare("
            SELECT * FROM " . self::$db_v_template . " 
            WHERE account_id = :account_id 
            ORDER BY `a_template_id` ASC
        ");

        Database::bind($stmt, ['account_id'], [$this->account_id]);
        Database::execute($stmt);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

}
