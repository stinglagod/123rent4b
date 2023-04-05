<?php

namespace rent\forms\manage\Client\Site;

use rent\entities\Client\Client;
use rent\entities\Client\Site;
use rent\entities\Shop\Category\Category;
use rent\entities\Shop\Product\Product;
use rent\repositories\Client\ClientRepository;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class SiteChangeForm extends Model
{
    public $site_id;

    public function __construct($site_id = null, $config = [])
    {
        if (($site_id) and (Site::findOne($site_id))) {
            $this->site_id = $site_id;
        } else {
            if (\Yii::$app->settings->site) {
                $this->site_id=\Yii::$app->settings->site->id;
            }
        }
        parent::__construct($config);
    }

    public function sitesList(): array
    {
//        if ($this->client_id)
//            return Site::find()->where(['client_id'=>$this->client_id])->orderBy('domain')->asArray()->all();
            return ArrayHelper::map(Site::find()->select(['id','concat(name,\' (\',domain,\' )\') as name'])->andWhere(['status'=>Site::STATUS_ACTIVE])->orderBy('domain')->asArray()->all(), 'id','name');
    }

    public function rules(): array
    {
        return [
//            [['site_id'], 'required'],
            [['site_id'], 'integer'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'site_id' => 'Сайт',
        ];
    }
}