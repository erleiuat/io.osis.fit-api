<?php

Core::class('Image/Image');

class Image_Admin extends Image {

    public static function delete($imageID) {

        $stmt1 = Database::prepare("
            DELETE FROM ". self::$db_t_img . " WHERE 
            `acc_image_id` = :acc_image_id
        ");

        Database::bind($stmt1, 
            ['acc_image_id'], 
            [$imageID]
        );

        Database::execute($stmt1);

    }

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
            $results[$key]["accountID"] = $value["account_id"];
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

        $obj = self::getObject((object) [
            "id" => $vals["acc_image_id"],
            "account_id" => $vals["account_id"],
            "folder" => $vals["folder"],
            "name" => $vals["name"],
            "mime" => $vals["mime"],
        ]);
        $obj["account_id"] = $vals["account_id"];

        return $obj;

    }

}
