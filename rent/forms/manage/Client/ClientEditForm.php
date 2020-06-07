<?php

namespace rent\forms\manage\Client;

use rent\entities\Client\Client;
use yii\base\Model;

class ClientEditForm extends Model
{
    public $name;
    public $status;

    public function __construct(Client $client, $config = [])
    {
        $this->name = $client->name;
        $this->status = $client->status;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100],
            ['status', 'default', 'value' => Client::STATUS_ACTIVE],
            ['status', 'in', 'range' => [Client::STATUS_ACTIVE, Client::STATUS_DELETED, Client::STATUS_NOT_ACTIVE]],
        ];
    }
}