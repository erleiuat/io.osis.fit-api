<?php

Core::plugin('Vali/Vali');

class Vali_Mail extends Vali {

    public static function val($name, $value, $required = false, $min = false, $max = 90) {

        $value = self::checkNull("Mail '" . $name . "'", "V0430", $value, $required);
        if ($value === null) return null;

        self::checkMinMax("Mail '" . $name . "'", "V0431", strlen($value), $min, $max);

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) 
            throw new ApiException(422, "V0433", "Mail '" . $name . "' invalid (incorrect format)");

        return $value;

    }

}