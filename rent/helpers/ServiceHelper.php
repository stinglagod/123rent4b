<?php

namespace rent\helpers;

use kartik\popover\PopoverX;
use rent\entities\Shop\Product\Product;
use rent\entities\Shop\Service;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ServiceHelper
{

### PopoverX. Описание полей
    public static function popoverX_byAttribute(string $attribute,string $name,array $options=[]):string
    {
        if ($description=Service::getDescriptionByAttribute($attribute)) {
            return self::defaultPopoverX($name,$description,$options);
        } else {
            return '';
        }

    }

###
    public static function defaultPopoverX(string $name,string $content,array $options=[]):string
    {

        return PopoverX::widget([
            'header' => 'Описание поля: '.$name,
            'placement' => PopoverX::ALIGN_RIGHT,
            'content' => $content,
            'toggleButton' => [
                'label' => '<span class="glyphicon glyphicon-question-sign"></span>',
                'style' => 'background: none; padding: 0; margin: 0; border: none; color: #31708f'
            ],
            'options' => $options
        ]);
    }


}