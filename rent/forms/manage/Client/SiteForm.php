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
    public $email;
    public $social;
    public $urlInstagram;
    public $urlTwitter;
    public $urlFacebook;
    public $urlGooglePlus;
    public $urlVk;
    public $urlOk;
    public $timezone;

    public function __construct(Site $site = null, $config = [])
    {
        if ($site) {
            $this->name = $site->name;
            $this->status = $site->status;
            $this->domain = $site->domain;
            $this->telephone = $site->telephone;
            $this->address = $site->address;
            $this->email = $site->email;
            $this->urlInstagram = $site->urlInstagram;
            $this->urlTwitter = $site->urlTwitter;
            $this->urlFacebook = $site->urlFacebook;
            $this->urlGooglePlus = $site->urlGooglePlus;
            $this->urlVk = $site->urlVk;
            $this->urlOk = $site->urlOk;
            $this->timezone = $site->timezone;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name','domain'], 'required'],
            [['name','domain'], 'string', 'max' => 100],
            [['address','email','urlInstagram','urlTwitter','urlFacebook','urlGooglePlus','urlVk','urlOk','timezone'], 'string', 'max' => 255],
//            TODO: проверка на телефон и email
            [['telephone'], 'string', 'max' => 100],
            ['status', 'default', 'value' => Site::STATUS_ACTIVE],
            ['status', 'in', 'range' => [Site::STATUS_ACTIVE, Site::STATUS_DELETED, Site::STATUS_NOT_ACTIVE]],
        ];
    }
}