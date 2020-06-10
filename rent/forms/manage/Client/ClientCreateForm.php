<?php

namespace rent\forms\manage\Client;

use rent\entities\Client\Client;
use yii\base\Model;

class ClientCreateForm extends Model
{
    public $name;
    public $status;

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100],
            ['status', 'default', 'value' => Client::STATUS_ACTIVE],
            ['status', 'in', 'range' => [\rent\entities\Client\Client::STATUS_ACTIVE, \rent\entities\Client\Client::STATUS_DELETED, \rent\entities\Client\Client::STATUS_NOT_ACTIVE]],
        ];
    }
}