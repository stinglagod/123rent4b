<?php

namespace rent\forms\manage\Client;

use rent\entities\Client\Site;
use yii\base\Model;

class SiteForm extends Model
{
    public $name;
    public $status;
    public $domain;
    public $telephone;
    public $address;

    public function __construct(Site $site = null, $config = [])
    {
        if ($site) {
            $this->name = $site->name;
            $this->status = $site->status;
            $this->domain = $site->domain;
            $this->telephone = $site->telephone;
            $this->address = $site->address;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name','domain'], 'required'],
            [['name','domain'], 'string', 'max' => 100],
            [['address'], 'string', 'max' => 255],
//            TODO: проверка на телефон
            [['telephone'], 'string', 'max' => 100],
            ['status', 'default', 'value' => Site::STATUS_ACTIVE],
            ['status', 'in', 'range' => [Site::STATUS_ACTIVE, Site::STATUS_DELETED, Site::STATUS_NOT_ACTIVE]],
        ];
    }
}