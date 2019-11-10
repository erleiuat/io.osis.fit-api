<?php

Core::environment("ENV_File");

class Image {

    protected static $db_t_img = "acc_image";

    public $id;
    public $account_id;
    public $folder;
    public $name;
    public $mime;

    public function __construct($account_id = false) {
        if ($account_id) $this->account_id = $account_id;
    }

    public static function getObject($el) {

        //if(!$el) $el = self;
        $pathRoot = ENV_File::static_url."/".ENV_File::folder."/".hash(ENV_File::hash_alg, $el->account_id)."/".$el->folder."/";

        return [
            "id" => $el->id,
            "folder" => $el->folder,
            "name" => $el->name,
            "mime" => $el->mime,
            "path" => $pathRoot.$el->name.".".$el->mime,
            "path_medium" => $pathRoot.$el->name."_medium.".$el->mime,
            "path_small" => $pathRoot.$el->name."_small.".$el->mime,
            "path_lazy" => $pathRoot.$el->name."_lazy.".$el->mime
        ];

    }

}