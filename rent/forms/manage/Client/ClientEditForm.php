<?php

namespace rent\forms\manage\Client;

use rent\entities\Client\Client;
use rent\forms\CompositeForm;

class ClientEditForm extends CompositeForm
{
    public $name;
    public $status;
    public $users;
    public $timezone;

    public function __construct(Client $client, $config = [])
    {
        $this->name = $client->name;
        $this->status = $client->status;
        $this->timezone = $client->timezone;

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name','timezone'], 'string', 'max' => 100],
            ['status', 'default', 'value' => Client::STATUS_ACTIVE],
            ['status', 'in', 'range' => [Client::STATUS_ACTIVE, Client::STATUS_DELETED, Client::STATUS_NOT_ACTIVE]],
        ];
    }

    protected function internalForms(): array
    {
        return [];
    }
}