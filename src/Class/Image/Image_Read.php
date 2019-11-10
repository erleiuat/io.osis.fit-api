<?php

Core::class('Image/Image');

class Image_Read extends Image {

    public function __construct($account_id = false, $image_id = false) {
        if ($account_id) parent::__construct($account_id);
        if ($image_id) $this->read($image_id);
    }

    public function read($image_id = false) {

        if ($image_id) $this->id = $image_id;

        $stmt = Database::prepare("
            SELECT * FROM " . self::$db_t_img . " 
            WHERE account_id = :account_id 
            AND acc_image_id = :acc_image_id
        ");

        Database::bind($stmt, 
            ['account_id', 'acc_image_id'], 
            [$this->account_id, $this->id]
        );
        Database::execute($stmt);

        $vals = $stmt->fetch();
        if (!$vals) throw new ApiException(404, "F0103", "File not found");

        $this->folder = $vals["folder"];
        $this->name = $vals["name"];
        $this->mime = $vals["mime"];

    }

}
