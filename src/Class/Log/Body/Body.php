<?php

class Body {

    protected static $db_t_log_body = "a_log_body";

    public $account_id;
    public $a_log_body_id;

    public $date;
    public $time;
    
    public $weight;
    public $fat;

    public function __construct($account_id = false) {
        if ($account_id) $this->account_id = $account_id;
    }

    public function set($arr) {

        $this->a_log_body_id = $arr["a_log_body_id"];

        $this->weight = $arr["weight"];
        $this->fat = $arr["fat"];

        if (isset($arr['stamp'])) {
            $tmpDate = DateTime::createFromFormat("Y-m-d H:i:s", $arr['stamp']);
            $this->date = $tmpDate->format('Y-m-d');
            $this->time = $tmpDate->format('H:i');
        } else {
            $this->date = $arr['date'];
            $this->time = $arr['time'];
        }

    }

    public static function formResponse($arr) {

        if (isset($arr['stamp'])) {
            $tmpDate = DateTime::createFromFormat("Y-m-d H:i:s", $arr['stamp']);
            $date = $tmpDate->format('Y-m-d');
            $time = $tmpDate->format('H:i');
        } else {
            $date = $arr['date'];
            $time = $arr['time'];
        }

        return (object) [
            "id" => (int) $arr['a_log_body_id'],
            "aLogBodyID" => (int) $arr['a_log_body_id'],
            "weight" => (double) $arr['weight'],
            "fat" => (double) $arr['fat'],
            "date" => $date,
            "time" => $time
        ];

    }

}