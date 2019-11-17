<?php

Core::environment("ENV_DB");

class Database {

    private static $db;
    private $connection;
    
    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host=" . ENV_DB::host . ";" .
                "dbname=" . ENV_DB::database,
                ENV_DB::username, ENV_DB::password
            );
            $this->connection->exec("SET NAMES utf8");
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            if (ENV_Main::env === "local") throw new ApiException(500, "D0201", ["msg" => "DB Connect Error", "e"=>$e]);
            throw new ApiException(500, "D0201", "DB Connect Error");
        }
    }

    public static function connect() {
        if (self::$db == null) self::$db = new Database();
        return self::$db->connection;
    }

    public static function prepare($query) {
        try {
            return self::$db->connection->prepare($query);
        } catch (PDOException $e) {
            if (ENV_Main::env === "local") throw new ApiException(500, "D0202", ["msg" => "DB Prepare Error", "e"=>$e]);
            throw new ApiException(500, "D0202", "DB Prepare Error");
        }
    }

    public static function bind($stmt, $params, $values) {
        try {
            $plengt = count($params);
            for ($i = 0; $i < $plengt; $i++) {
                $stmt->bindParam($params[$i], $values[$i]);
            }
        } catch (PDOException $e) {
            if (ENV_Main::env === "local") throw new ApiException(500, "D0203", ["msg" => "DB Bind Error", "e"=>$e]);
            throw new ApiException(500, "D0203", "DB Bind Error");
        }
    }

    public static function execute($stmt) {
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            if (ENV_Main::env === "local") throw new ApiException(500, "D0204", ["msg" => "DB Execute Error", "e"=>$e]);
            throw new ApiException(500, "D0204", "DB Execute Error");
        }
    }

    public static function getID() {
        try {
            return self::$db->connection->lastInsertId();
        } catch (PDOException $e) {
            if (ENV_Main::env === "local") throw new ApiException(500, "D0205", ["msg" => "Unable to get insert ID", "e"=>$e]);
            throw new ApiException(500, "D0205", "Unable to get insert ID");
        }
    }

}