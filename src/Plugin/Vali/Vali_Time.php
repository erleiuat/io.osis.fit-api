<?php

Core::plugin('Vali/Vali');

class Vali_Time extends Vali {

    public static function val($name, $value, $required = false, $earliest = "00:00", $latest = "23:59") {

        $value = self::checkNull("Time '" . $name . "'", "V0470", $value, $required);
        if ($value === null) return null;

        $vals = explode(':', $value);

        if (count($vals) !== 2 || $vals[0] > 23 || $vals[1] > 59 || $vals[0] < 0 || $vals[1] < 0)
            throw new ApiException(422, "V0471", "Time (" . $name . ") invalid");

        if ($earliest !== false && $earliest > $value) 
            throw new ApiException(422, "V0472", "Time (" . $name . ") too early (Min: " . $earliest . ")");

        if ($latest !== false && $latest < $value) 
            throw new ApiException(422, "V0473", "Time (" . $name . ") too late (Max: " . $latest . ")");

        return $vals[0] . ":" . $vals[1];

    }

}