<?php

namespace rent\forms\manage\Client;

use rent\entities\Client\Client;
use rent\entities\Client\Site;
use rent\entities\Shop\Category\Category;
use rent\entities\Shop\Product\Product;
use rent\repositories\Client\ClientRepository;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class ClientChangeForm extends Model
{
    public $client_id;

    public function __construct($client_id = null, $config = [])
    {
        if (($client_id) and (Client::findOne($client_id))) {
            $this->client_id = $client_id;
        } else {
            $this->client_id=\Yii::$app->settings->client->id;
        }
        parent::__construct($config);
    }

    public function clientsList(): array
    {
        return ArrayHelper::map(Client::find()->orderBy('name')->asArray()->all(), 'id','name');
    }

    public function sitesList(): array
    {
        if ($this->client_id)
//            return Site::find()->where(['client_id'=>$this->client_id])->orderBy('domain')->asArray()->all();
            return ArrayHelper::map(Site::find()->where(['client_id'=>$this->client_id])->orderBy('domain')->asArray()->all(), 'id','name');
    }

    public function rules(): array
    {
        return [
            [['client_id'], 'required'],
            [['client_id'], 'integer'],
        ];
    }
}