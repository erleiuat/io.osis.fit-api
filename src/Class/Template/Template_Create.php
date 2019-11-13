<?php

Core::class('Template/Template');

class Template_Create extends Template {

    public function create() {

        $stmt = Database::prepare("
            INSERT INTO ".self::$db_t_template."
            (`account_id`, `image`, `title`, `calories_per_100`, `fat_per_100`, `protein_per_100`, `portion_size`) VALUES 
            (:account_id, :image, :title, :calories_per_100, :fat_per_100, :protein_per_100, :portion_size);
        ");

        Database::bind($stmt, 
            ['account_id', 'image', 'title', 'calories_per_100', 'fat_per_100', 'protein_per_100', 'portion_size'], 
            [$this->account_id, $this->image, $this->title, $this->calories_per_100, $this->fat_per_100, $this->protein_per_100, $this->portion_size]
        );

        Database::execute($stmt);
        
        $this->a_template_id = Database::getID();

    }

}