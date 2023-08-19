<?php

namespace App\Util;

class UserUtil
{
    public static function generatePlainPassword($lenght = 8): string
    {
        $pwd = '';
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $lenght; ++$i) {
            $n = rand(0, $alphaLength);
            $pwd .= $alphabet[$n];
        }

        return $pwd;
    }
}
