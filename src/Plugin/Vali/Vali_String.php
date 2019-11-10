<?php

Core::plugin('Vali/Vali');

class Vali_String extends Vali {

    /** $encode = remove <script>-tags and similar */
    public static function val($name, $value, $required = false, $min = false, $max = false, $encode = false) {
        
        $value = self::checkNull("String '" . $name . "'", 420, $value, $required);
        if ($value === null) return null;

        if ($encode) $value = filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        self::checkMinMax("String '" . $name . "'", 421, strlen($value), $min, $max);
        
        return $value;

    }

}