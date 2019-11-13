<?php

class Template {

    protected static $db_t_template = "a_template";
    protected static $db_v_template = "v_template";

    public $account_id;
    public $a_template_id;

    public $title;
    public $calories_per_100;
    public $fat_per_100;
    public $protein_per_100;
    public $portion_size;
    public $image;

    public function __construct($account_id = false) {
        if ($account_id) $this->account_id = $account_id;
    }

    public function set($arr) {
        $this->title = $arr['title'];
        $this->calories_per_100 = $arr['calories_per_100'];
        $this->fat_per_100 = $arr['fat_per_100'];
        $this->protein_per_100 = $arr['protein_per_100'];
        $this->portion_size = $arr['portion_size'];
        $this->image = $arr['image'];
    }

    public static function formResponse($arr) {

        return (object) [
            "id" => (int) $arr['a_template_id'],
            "aTemplateID" => (int) $arr['a_template_id'],
            "title" => $arr['title'],
            "caloriesPer100" => $arr['calories_per_100'] ? (double) $arr['calories_per_100'] : null,
            "fatPer100" => $arr['fat_per_100'] ? (double) $arr['fat_per_100'] : null,
            "proteinPer100" => $arr['protein_per_100'] ? (double) $arr['protein_per_100'] : null,
            "portionSize" => $arr['portion_size'] ? (double) $arr['portion_size'] : null,
            "image" => $arr['image']
        ];

    }

}