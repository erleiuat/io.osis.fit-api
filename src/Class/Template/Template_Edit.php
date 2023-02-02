<?php

Core::class('Template/Template_Read');

class Template_Edit extends Template_Read {

  public function __construct($account_id, $template_id) {
    parent::__construct($account_id);
    $this->a_template_id = $template_id;
    $this->set($this->readById($template_id));
  }

  public function edit() {

    $stmt = Database::prepare("
            UPDATE " . self::$db_t_template . " SET 
            `title` = :title,
            `calories_per_100` = :calories_per_100,
            `fat_per_100` = :fat_per_100,
            `protein_per_100` = :protein_per_100,
            `carbs_per_100` = :carbs_per_100,
            `portion_size` = :portion_size,
            `image` = :image
            WHERE `a_template_id` = :a_template_id
            AND `account_id` = :account_id
        ");

    Database::bind(
      $stmt,
      ['a_template_id', 'account_id', 'image', 'title', 'calories_per_100', 'fat_per_100', 'protein_per_100', 'carbs_per_100', 'portion_size'],
      [$this->a_template_id, $this->account_id, $this->image, $this->title, $this->calories_per_100, $this->fat_per_100, $this->protein_per_100, $this->carbs_per_100, $this->portion_size]
    );

    Database::execute($stmt);
  }
}
