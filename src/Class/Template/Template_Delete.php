<?php

Core::class('Template/Template_Edit');

class Template_Delete extends Template_Edit {

    public function delete() {

        $stmt = Database::prepare("
            DELETE FROM ".self::$db_t_template." 
            WHERE `a_template_id` = :a_template_id 
            AND `account_id` = :account_id
        ");

        Database::bind($stmt, 
            ['a_template_id', 'account_id'], 
            [$this->a_template_id, $this->account_id]
        );

        Database::execute($stmt);

    }

    

}