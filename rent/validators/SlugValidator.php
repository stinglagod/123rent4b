<?php

namespace rent\validators;

use yii\validators\RegularExpressionValidator;

class SlugValidator extends RegularExpressionValidator
{
    public $pattern = '/^[a-z_-][a-z0-9_-]*/s';
    public $message = 'Only [a-z0-9_-] symbols are allowed.';
}