<?php

namespace rent\helpers;

use rent\entities\User\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class PasswordHelper
{
    public static function generate(int $length = 8): string
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
        $length = rand(10, 16);
        $password = substr( str_shuffle(sha1(rand() . time()) . $chars ), 0, $length );
        return $password;
    }
}