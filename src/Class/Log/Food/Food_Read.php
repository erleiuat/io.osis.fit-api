<?php

Core::class('Log/Food/Food');

class Food_Read extends Food {

    public function readById($id) {

        $stmt = Database::prepare("
            SELECT * FROM " . self::$db_t_log_food . " WHERE 
            account_id = :account_id AND
            a_log_food_id = :a_log_food_id
        ");

        Database::bind($stmt, ['account_id', 'a_log_food_id'], [$this->account_id, $id]);
        Database::execute($stmt);

        $val = $stmt->fetch();
        if (!$val) throw new ApiException(404, "E0001", "Entry not found (".get_class($this)."->".__FUNCTION__.")");
        return $val;

    }

    public function read($from = false, $to = false) {

        $sql = "SELECT * FROM " . self::$db_t_log_food . " WHERE account_id = :account_id";
        if ($from) $sql .= " AND stamp >= CONCAT(:from, ' 00:00:00')";
        if ($to) $sql .= " AND stamp <= CONCAT(:to, ' 23:59:59')";
        $sql .= " ORDER BY `stamp` DESC";

        $stmt = Database::prepare($sql);
        if ($from) Database::bind($stmt, ['from'], [$from]);
        if ($to) Database::bind($stmt, ['to'], [$to]);
        Database::bind($stmt, ['account_id'], [$this->account_id]);

        Database::execute($stmt);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

}