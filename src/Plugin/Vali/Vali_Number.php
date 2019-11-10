<?php

Core::plugin('Vali/Vali');

class Vali_Number extends Vali {

    public static function val($name, $value, $required = false, $min = false, $max = false) {

        $value = self::checkNull("Number '" . $name . "'", "V0410", $value, $required);
        if ($value === null) return null;

        if (!is_numeric($value)) throw new ApiException(422, "V0411", "Number '" . $name . "' invalid (not numeric)");
        $value = floatval($value);
        self::checkMinMax("Number '" . $name . "'", "V0412", $value, $min, $max);
   
        return $value;

    }

}