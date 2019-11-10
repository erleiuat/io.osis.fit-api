<?php

class Admin_Codes {

    protected static $db_t_codes = "sys_error_codes";

    public static function read() {

        $stmt = Database::prepare("SELECT * FROM " . self::$db_t_codes);
        Database::execute($stmt);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;

    }

}

