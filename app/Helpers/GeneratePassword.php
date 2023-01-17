<?php

namespace App\Helpers;


class GeneratePassword extends ApiController
{

    public static function randPassword($length){

        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars), 0, $length);
    }

   
}
