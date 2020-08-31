<?php

namespace backend\bootstrap;
use Yii;

class Settings
{
    public $client_id;
    public $site_id;
    public $timezone;

    public function __construct($client_id,$site_id,$timezone)
    {
        $this->client_id=$client_id;
        $this->site_id=$site_id;
        $this->timezone=$timezone;
    }

    public static function load()
    {
        return Yii::$app->session->get('settings');
    }
    public function save()
    {

        Yii::$app->session->set('settings',$this);
    }
}