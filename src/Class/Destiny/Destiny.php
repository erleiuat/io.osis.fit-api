<?php

class Destiny {

    protected static $db_t_goals = "a_destiny_goals";
    protected static $db_t_metabolism = "a_destiny_metabolism";

    public $account_id;

    public function __construct($account_id = false) {
        if ($account_id) $this->account_id = $account_id;
    }

}