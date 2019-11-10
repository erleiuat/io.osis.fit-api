<?php

Core::class('Image/Image');

class Image_Admin extends Image {

    public static function readall($from = 0, $to = 50) {

        $stmt = Database::prepare("
            SELECT * FROM " . self::$db_t_img . "
            ORDER BY `created` DESC
            LIMIT ".$from.", ".$to."
        ");

        Database::execute($stmt);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $key => $value) {
            $value["id"] = $value["acc_image_id"];
            $results[$key] = self::getObject((object) $value);
        }

        return $results;

    }

    public static function readObject($image_id) {

        $stmt = Database::prepare("
            SELECT * FROM " . self::$db_t_img . " 
            WHERE acc_image_id = :image_id
        ");

        Database::bind($stmt, ['image_id'], [$image_id]);
        Database::execute($stmt);

        $vals = $stmt->fetch();
        if (!$vals) throw new ApiException(404, "F0103", "File not found");

        return self::getObject((object) [
            "id" => $vals["acc_image_id"],
            "account_id" => $vals["account_id"],
            "folder" => $vals["folder"],
            "name" => $vals["name"],
            "mime" => $vals["mime"],
        ]);

    }

}
