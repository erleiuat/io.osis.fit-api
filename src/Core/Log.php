<?php

class Log {

    private static $db_t_sys_log = "sys_log";
    
    private static $identifier = null;
    private static $process = null;
    private static $information = "";
    private static $trace = null;

    public static $level = 'trace';
    
    public static function init() { 

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        self::$trace = (
            "URL=" . $_SERVER['REQUEST_URI'] . " | " .
            "IP=" . $ip . " | " .
            "USER_AGENT=" . $_SERVER['HTTP_USER_AGENT'] . " | " .
            "REMOTE_ADDRESS=" . $_SERVER['REMOTE_ADDR'] . " | " .
            "REMOTE_PORT=" . $_SERVER['REMOTE_PORT'] . " | " .
            "PHP_SELF=" . $_SERVER['PHP_SELF'] . " | "
        );

        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            self::$trace .= "X_FORWARDED_FOR=" . $_SERVER['HTTP_X_FORWARDED_FOR'] . "| ";
        }

        if (isset($_SERVER['HTTP_REFERER'])) {
            self::$trace .= "REFERER=" . $_SERVER['HTTP_REFERER'] . " | ";
        }
        
    }

    public static function identifier($identifier) {
        self::$identifier = $identifier . " | " . self::$identifier;
    }

    public static function setProcess($process) {
        self::$process = $process;
    }

    /** trace, debug, info, warn, error, fatal */
    public static function setLevel($level) {
        self::$level = $level;
        return;
    }

    public static function addInformation($info) {
        self::$information = $info . "| " . self::$information;
    }

    public static function write($levels) {

        if (in_array(self::$level, $levels)) {

            $stmt = Database::prepare("
                INSERT INTO ".self::$db_t_sys_log . " 
                (`level`, `identifier`, `process`, `information`, `trace`) VALUES 
                (:level, :identifier, :process, :information, :trace);
            ");
            
            Database::bind($stmt, 
                ['level', 'identifier', 'process', 'information', 'trace'], 
                [self::$level, self::$identifier, self::$process, self::$information, self::$trace]
            );

            Database::execute($stmt);

        }

    }

}