<?php

namespace rent\forms\manage\Client\Site;

use rent\entities\Client\Site\ReCaptcha;
use yii\base\Model;


class ReCaptchaForm extends Model
{
    public $google_siteKeyV3;
    public $google_secretV3;

    public function __construct(ReCaptcha $reCaptcha=null,$config = [])
    {
        if ($reCaptcha) {
            $this->google_siteKeyV3=$reCaptcha->google_siteKeyV3;
            $this->google_secretV3=$reCaptcha->google_secretV3;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['google_siteKeyV3','google_secretV3'], 'string'],
        ];
    }

}