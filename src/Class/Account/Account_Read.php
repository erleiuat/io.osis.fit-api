<?php

Core::class('Account/Account');

class Account_Read extends Account {

    /** $detailed: With details like firstname, lastname, etc. */
    public function __construct($id = false, $detailed = false) {

        if (!$id) return;
        $this->id = $id;

        $stmt = Database::prepare("SELECT * FROM " . self::$db_t_acc . " WHERE account_id = :account_id");
        Database::bind($stmt, ['account_id'], [$this->id]);
        Database::execute($stmt);

        $vals = $stmt->fetch();
        if (!$vals) throw new ApiException(404, "A1002", "Account not found");

        $this->mail = $vals["mail"];
        $this->username = $vals["username"];
        $this->level = $vals["level"];
        $this->status = $vals["status"];

        if (!$detailed) return;

        $this->details();

    }

    public static function all($from = 0, $to = 100, $identity = false, $status = false) {

        $sql = "SELECT * FROM " . self::$db_v_acc_detailed;

        if ($identity) {
            $sql .= " WHERE (
                `account_id` LIKE :identity OR 
                `mail` LIKE :identity OR 
                `username` LIKE :identity OR 
                `firstname` LIKE :identity OR 
                `lastname` LIKE :identity 
            )";
            if ($status) $sql .= " AND `status` = :status";
        } else if ($status) $sql .= " WHERE `status` = :status";

        $sql .= " LIMIT " . intval($from) . ", " . intval($to);
        $stmt = Database::prepare($sql);

        if ($identity) {
            if ($status) Database::bind($stmt, ['status', 'identity'], [$status, '%' . $identity . '%']);
            else Database::bind($stmt, ['identity'], ['%' . $identity . '%']);
        } else if ($status) Database::bind($stmt, ['status'], [$status]);

        Database::execute($stmt);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
        
    }

    public function details() {

        $stmt = Database::prepare("SELECT * FROM " . self::$db_t_acc_detail . " WHERE account_id = :account_id");
        Database::bind($stmt, ['account_id'], [$this->id]);
        Database::execute($stmt);

        $vals = $stmt->fetch();
        if (!$vals) throw new ApiException(404, "A1003", "Account (Details) not found");

        $this->firstname = $vals["firstname"];
        $this->lastname = $vals["lastname"];
        $this->birthdate = $vals["birthdate"];
        $this->locale = $vals["locale"];
        $this->avatar = $vals["avatar"];

    }

}