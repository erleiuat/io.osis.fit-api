<?php

class Food {

    protected static $db_t_log_food = "a_log_food";

    public $account_id;
    public $a_log_food_id;

    public $date;
    public $time;
    
    public $title;
    public $total_calories;
    public $total_fat;
    public $total_protein;
    public $portion_size;

    public function __construct($account_id = false) {
        if ($account_id) $this->account_id = $account_id;
    }

    public function set($arr) {

        $this->a_log_food_id = $arr["a_log_food_id"];

        $this->title = $arr["title"];
        $this->total_calories = $arr["total_calories"];
        $this->total_fat = $arr["total_fat"];
        $this->total_protein = $arr["total_protein"];
        $this->portion_size = $arr["portion_size"];

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
            "id" => (int) $arr['a_log_food_id'],
            "aLogFoodID" => (int) $arr['a_log_food_id'],
            "title" => $arr['title'],
            "totalCalories" => (double) $arr['total_calories'],
            "totalFat" => (double) $arr['total_fat'],
            "totalProtein" => (double) $arr['total_protein'],
            "portionSize" => (double) $arr['portion_size'],
            "date" => $date,
            "time" => $time
        ];

    }

}