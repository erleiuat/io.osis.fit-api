<?php

class Admin_Log {

    protected static $db_t_log = "sys_log";

    public static function read($level = false, $identity = false, $from = 0, $to = 100) {

        $sql = "SELECT * FROM " . self::$db_t_log;

        if ($level) {
            $sql .= " WHERE `level` LIKE :level";
            if ($identity) $sql .= " AND `identifier` LIKE :identity";
        } else if ($identity) $sql .= " WHERE `identifier` LIKE :identity";

        $sql .= " ORDER BY `id` DESC LIMIT " . intval($from) . ", " . intval($to);
        $stmt = Database::prepare($sql);

        if ($level) {
            if ($identity) Database::bind($stmt, ['level', 'identity'], ['%' .$level. '%', '%' . $identity . '%']);
            else Database::bind($stmt, ['level'], ['%' .$level. '%']);
        } else if ($identity) Database::bind($stmt, ['identity'], ['%' . $identity . '%']);

        Database::execute($stmt);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;

    }

    public static function delete($level) {

        $stmt = Database::prepare("
            DELETE FROM ".self::$db_t_log." 
            WHERE `level` = :level
        ");

        Database::bind($stmt, ['level'], [$level]);

        Database::execute($stmt);

    }

}

