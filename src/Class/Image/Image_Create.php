<?php

Core::class('Image/Image');

class Image_Create extends Image {

    /** Set name of image with removed mime tag */
    public function setName($fileName) {
        $tmpName = explode(".", $fileName);
        array_pop($tmpName);
        if (count($tmpName) > 1) $this->name = implode(".", $tmpName);
        else $this->name = $tmpName[0];
    }

    public function create() {

        $unique = uniqid('', true);
        $time = date('Y_m_d_H_i_s', time());
        $this->id = hash('ripemd160', $time . ':' . $unique);

        $stmt1 = Database::prepare("
            INSERT INTO ". self::$db_t_img . " 
            (`acc_image_id`, `account_id`, `folder`, `name`, `mime`) VALUES 
            (:acc_image_id, :account_id, :folder, :name, :mime);
        ");
        
        Database::bind($stmt1, 
            ['acc_image_id', 'account_id', 'folder', 'name', 'mime'], 
            [$this->id, $this->account_id, $this->folder, $this->name, $this->mime]
        );

        Database::execute($stmt1);

    }

    public static function correctOrientation($filename) {
        if (function_exists('exif_read_data')) {
            $exif = exif_read_data($filename);
            if($exif && isset($exif['Orientation'])) {
                $orientation = $exif['Orientation'];
                if($orientation != 1){
                    $img = imagecreatefromjpeg($filename);
                    $deg = 0;
                    switch ($orientation) {
                        case 3:
                            $deg = 180;
                            break;
                        case 6:
                            $deg = 270;
                            break;
                        case 8:
                            $deg = 90;
                            break;
                    }
                    if ($deg) $img = imagerotate($img, $deg, 0);
                    imagejpeg($img, $filename, 100);
                    return true;
                }
            }
        }
    }

}
