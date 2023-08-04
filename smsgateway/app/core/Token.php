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

    static function uuid()
    {
        $time = microtime(true);
        $rand = rand(1000, 9999);
        $hash = $time . $rand;
        $uuid = md5($hash);
        //$uuid = hash('sha256', $hash);
        $guidText =
            substr($uuid, 0, 8) . '-' .
            substr($uuid, 8, 4) . '-' .
            substr($uuid, 12, 4) . '-' .
            substr($uuid, 16, 4) . '-' .
            substr($uuid, 20);
        return $guidText;
    }
}
