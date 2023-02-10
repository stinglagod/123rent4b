<?php

namespace rent\helpers;

use rent\entities\User\User;
use rent\forms\manage\User\UserCreateForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class TextHelper
{
    public static function formatPrice(float $price,string $currency=''): string
    {
        if (empty($price))
            return '';
        return number_format($price,2,',',' ') .' '. $currency;
    }

    public static function transliterateCyrToLatin(string $text):string
    {
        return TextHelper::transliterate($text);
    }
    public static function transliterateLatinToCyr(string $text):string
    {
        return TextHelper::transliterate(null,$text);
    }
    public static function replaceSpecialChar(string $text):string
    {
        $specialChars = [' ','/','"',"'",'$',',','.',':','!','?','\\',];
        $replaceChars = ['_','_','' ,'' , '','' ,'' ,'' ,'' ,'' ,'' ,];
        return str_replace($specialChars, $replaceChars, $text);
    }
###
    private static function transliterate($textCyr = null, $textLat = null):?string
    {
        $cyr = array(
            'ж',  'ч',  'щ',   'ш',  'ю',  'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ъ', 'ь', 'я',
            'Ж',  'Ч',  'Щ',   'Ш',  'Ю',  'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ъ', 'Ь', 'Я');
        $lat = array(
            'zh', 'ch', 'sht', 'sh', 'yu', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'y', 'x', 'ya',
            'Zh', 'Ch', 'Sht', 'Sh', 'Yu', 'A', 'B', 'V', 'G', 'D', 'E', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'c', 'Y', 'X', 'Ya');
        if($textCyr) return str_replace($cyr, $lat, $textCyr);
        else if($textLat) return str_replace($lat, $cyr, $textLat);
        else return null;
    }
}