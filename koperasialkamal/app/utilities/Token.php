<?php
class Token
{
    static function generate($strength = 20)
    {
        $input = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $random = substr(str_shuffle($input), 0, $strength);
        return $random;
    }

    static function OTP($strength = 5)
    {
        $input = '0123456789';
        $random = substr(str_shuffle($input), 0, $strength);
        return $random;
    }

    static function resetPassword($strength = 6)
    {
        $input = '0123456789abcdefghijklmnopqrstuvwxyz';
        $random = substr(str_shuffle($input), 0, $strength);
        return $random;
    }
}
