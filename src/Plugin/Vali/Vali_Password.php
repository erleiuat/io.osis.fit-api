<?php

Core::plugin('Vali/Vali');

class Vali_Password extends Vali {

    /** $format = require at least one uppercase/lowercase/number/special (special = @, #, ;, ...) */
    public static function val($name, $value, $required = false, $min = false, $max = false, $format = ['uppercase', 'lowercase', 'number', 'special']) {

        $value = self::checkNull("Password '" . $name . "'", "V0440", $value, $required);
        if ($value === null) return null;

        self::checkMinMax("Password '" . $name . "'", "V0441", strlen($value), $min, $max);

        if (in_array('uppercase', $format) && !preg_match("#[A-Z]+#", $value)) 
            throw new ApiException(422, "V0442", "Password '" . $name . "' requires uppercase");

        if (in_array('lowercase', $format) && !preg_match("#[a-z]+#", $value)) 
            throw new ApiException(422, "V0443", "Password '" . $name . "' requires lowercase");

        if (in_array('number', $format) && !preg_match("#[0-9]+#", $value)) 
            throw new ApiException(422, "V0444", "Password '" . $name . "' requires number");

        if (in_array('special', $format) && !preg_match("#[^\w]+#", $value)) 
            throw new ApiException(422, "V0445", "Password '" . $name . "' requires special character");

        return $value;

    }

}