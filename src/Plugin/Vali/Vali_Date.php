<?php

Core::plugin('Vali/Vali');

class Vali_Date extends Vali {

    public static function val($name, $value, $required = false, $earliest = "1900-01-01", $latest = false) {

        $value = self::checkNull("Date '" . $name . "'", "V0460", $value, $required);
        if ($value === null) return null;
        
        $vals = explode('-', $value);

        if (count($vals) !== 3)
            throw new ApiException(422, "V0461", "Date (" . $name . ") invalid");
        
        if (!checkdate(intval($vals[1]), intval($vals[2]), intval($vals[0]))) 
            throw new ApiException(422, "V0462", "Date (" . $name . ") invalid (check failed)");


        if ($earliest !== false && $earliest > $value) 
            throw new ApiException(422, "V0463", "Date (" . $name . ") too early (Min: " . $earliest . ")");

        if ($latest !== false && $latest < $value) 
            throw new ApiException(422, "V0464", "Date (" . $name . ") too late (Max: " . $latest . ")");


        return $vals[0] . "-" . $vals[1] . "-" . $vals[2];

    }

}