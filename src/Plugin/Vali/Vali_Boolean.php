<?php

Core::plugin('Vali/Vali');

class Vali_Boolean extends Vali {

    public static function val($name, $value, $required = false) {
        
        if ($value === false) $value = "false";
        self::checkNull("Boolean '" . $name . "'", 450, $value, $required);

        if ($value === null) return null;
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);

    }

}