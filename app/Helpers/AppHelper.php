<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class AppHelper
{

    public function __construct()
    {
    }

    public static function generateRandomString($length = 10)
    {
        $characters = '23456789abcdefghkmnpqrstuwxyzABCDEFGHJKLMNPQRSTUWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
