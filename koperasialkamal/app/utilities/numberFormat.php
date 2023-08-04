<?php
class Numeric
{
    static function numberFormat($data = NULL)
    {
        if ($data == NULL || $data == '' || $data == '0') {
            $result = '0';
        } else {
            $result = @number_format($data, 0, '', '.');
        }
        return $result;
    }

    static function random($min, $max)
    {
        $float_part = mt_rand(0, mt_getrandmax()) / mt_getrandmax();
        $integer_part = mt_rand($min, $max - 1);
        return $integer_part + $float_part;
    }
}

//=========== USING RANDOM NUMBER
// Numeric::random(0.1,5);
// round(Numeric::random(0.1,5),1);