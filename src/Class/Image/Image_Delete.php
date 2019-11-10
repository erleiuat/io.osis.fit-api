<?php

Core::class('Image/Image_Read');

class Image_Delete extends Image_Read {

    public function delete($image_id = false) {

        if ($image_id) $this->read($image_id);

        $stmt1 = Database::prepare("
            DELETE FROM ". self::$db_t_img . " WHERE 
            `account_id` = :account_id AND 
            `acc_image_id` = :acc_image_id
        ");

        Database::bind($stmt1, 
            ['account_id', 'acc_image_id'], 
            [$this->account_id, $this->id]
        );

        Database::execute($stmt1);

    }

}
