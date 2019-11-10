<?php

class Account {

    protected static $db_t_acc = "account";
    protected static $db_t_acc_detail = "acc_detail";
    protected static $db_t_acc_preferences = "acc_preferences";
    protected static $db_t_acc_verification = "acc_verification";
    protected static $db_v_acc_detailed = "v_acc_detailed";

    public $id;
    public $mail;
    public $username;
    public $level;
    public $status;
    
    public $firstname;
    public $lastname;
    public $birthdate;
    public $locale;
    public $avatar;

    protected $password;

    public static function getIdByMail($mail) {

        $stmt = Database::prepare("
            SELECT account_id FROM ". self::$db_t_acc . " WHERE 
            mail = :mail
        ");

        Database::bind($stmt, 
            ['mail'], [$mail]
        );

        Database::execute($stmt);
        $vals = $stmt->fetchAll();

        if (count($vals) > 0) return $vals[0]["account_id"];
        return false;

    }

    public static function getIdByUsername($username) {

        $stmt = Database::prepare("
            SELECT account_id FROM ". self::$db_t_acc . " WHERE 
            username = :username
        ");

        Database::bind($stmt, 
            ['username'], [$username]
        );

        Database::execute($stmt);
        $vals = $stmt->fetchAll();

        if (count($vals) > 0) return $vals[0]["account_id"];
        return false;

    }

    public function setPassword($password) {
        $this->password = password_hash($password, ENV_Main::encryption);
    }

    public function verifyPassword($password) {

        $stmt = Database::prepare("SELECT password FROM " . self::$db_t_acc . " WHERE account_id = :account_id");
        Database::bind($stmt, ['account_id'], [$this->id]);
        Database::execute($stmt);

        $vals = $stmt->fetch();
        if (!$vals) throw new ApiException(404, "A1002", "Account not found");

        if (password_verify($password, $vals["password"])) return true;
        return false;

    }

}