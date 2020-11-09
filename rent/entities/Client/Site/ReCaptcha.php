<?php

namespace rent\entities\Client\Site;


use rent\entities\abstracts\JsonAbstract;
use rent\forms\manage\Client\Site\CounterForm;
use rent\forms\manage\Client\Site\ReCaptchaForm;
use yii\helpers\Json;

class ReCaptcha extends JsonAbstract
{
    public $google_secretV3;
    public $google_siteKeyV3;

    public function __construct(string $json=null, ReCaptchaForm $reCaptchaForm=null)
    {
        if ($json) {
            $this->set(Json::decode($json));
        } elseif ($reCaptchaForm) {
            $this->google_secretV3=$reCaptchaForm->google_secretV3;
            $this->google_siteKeyV3=$reCaptchaForm->google_siteKeyV3;
        }
    }
}