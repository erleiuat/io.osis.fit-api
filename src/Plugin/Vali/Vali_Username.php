<?php

Core::plugin('Vali/Vali');

class Vali_Username extends Vali {

    public static function val($name, $value, $required = false, $min = false, $max = false) {
        
        $value = self::checkNull("Username '" . $name . "'", "V0480", $value, $required);
        if ($value === null) return null;

        if (strpos($value, ' ') !== false)
            throw new ApiException(422, "V0481", "Username '" . $name . "' invalid (spaces)");
        else if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new ApiException(422, "V0482", "Username '" . $name . "' should not be an email");
        }

        self::checkMinMax("Username '" . $name . "'", "V0483", strlen($value), $min, $max);
        
        return $value;

    }

}