<?php

class Vali {

    public static function checkNull($name, $err_code, $value, $required) {

        $value = trim($value);
        if (strlen($value) > 0) return $value;
        if ($required !== false) throw new ApiException(422, $err_code, $name . " required");
        return null;

    }

    public static function checkMinMax($name, $err_code, $value, $min, $max) {

        if ($min !== false && $value < $min) 
            throw new ApiException(422, $err_code, $name . " invalid (Min: " . $min . ")");

        if ($max !== false && $value > $max) 
            throw new ApiException(422, $err_code, $name . " invalid (Max: " . $max . ")");

    }

}