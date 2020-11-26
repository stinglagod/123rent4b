<?php

namespace rent\validators;

use yii\validators\RegularExpressionValidator;

class SlugValidator extends RegularExpressionValidator
{
    public $pattern = '#^[^\d].*#s';
    public $message = 'Only [a-z0-9_-] symbols are allowed.';
}