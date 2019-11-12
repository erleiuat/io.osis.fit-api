<?php

class Activity {

    protected static $db_t_log_activity = "a_log_activity";

    public $account_id;
    public $a_log_activity_id;

    public $date;
    public $time;
    
    public $title;
    public $burned_calories;
    public $type;

    public function __construct($account_id = false) {
        if ($account_id) $this->account_id = $account_id;
    }

    public function set($arr) {

        $this->a_log_activity_id = $arr["a_log_activity_id"];

        $this->title = $arr["title"];
        $this->burned_calories = $arr["burned_calories"];
        $this->type = $arr["type"];

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
            "id" => (int) $arr['a_log_activity_id'],
            "aLogActivityID" => (int) $arr['a_log_activity_id'],
            "title" => $arr['title'],
            "burnedCalories" => (double) $arr['burned_calories'],
            "type" => $arr['type'],
            "date" => $date,
            "time" => $time
        ];

    }

}