<?php

namespace rent\entities\Shop;

use rent\entities\Shop\Order\Order;
use yii\db\ActiveRecord;
use rent\entities\Client\Site;
use yii\db\ActiveQuery;
use rent\entities\behaviors\ClientBehavior;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * @property integer $id
 * @property string $name
 * @property float $percent
 * @property boolean $is_depend
 * @property float $defaultCost
 * @property integer $site_id
 * @property integer $client_id
 * @property integer $status
 *
 * @property Site $site
 *
 */
class Service extends ActiveRecord
{
    const STATUS_ACTIVE=10;
    const STATUS_NOT_ACTIVE=20;
    const STATUS_DELETED=100;
    public static function create(string $name,  $percent, $is_depend, $defaultCost,$status): self
    {
        $service = new static();
        $service->name = $name;
        $service->percent = $percent;
        $service->is_depend = $is_depend;
        $service->defaultCost = $defaultCost;
        $service->status=empty($status)?self::STATUS_ACTIVE:$status;
        return $service;
    }

    public function edit(string $name,$percent,$is_depend,$defaultCost,$status): void
    {
        $this->name = $name;
        $this->percent = $percent;
        $this->is_depend = $is_depend;
        $this->defaultCost = $defaultCost;
        $this->status = $status;
    }

    public static function tableName(): string
    {
        return '{{%shop_services}}';
    }

    public function behaviors(): array
    {
        return [
            ClientBehavior::class,
        ];
    }

    public function getSite() :ActiveQuery
    {
        return $this->hasOne(Site::class, ['id' => 'site_id']);
    }

    public static function find($all=false)
    {
//        if (Yii::$app->user->can('super_admin')) {
//            $all=true;
//        }
        if ($all) {
            return parent::find();
        } else {
            return parent::find()->andWhere(['client_id' => Yii::$app->settings->getClientId()]);
        }
    }

### Описание полей

    /**
     * Стандартный метод
     * @return string[]|null
     */
    public function attributeLabels()
    {
        return self::getAttributeLabels();
    }

    /**
     * Вывел в статику, что бы иметь доступ извне.
     * @return void
     */
    public static function getAttributeLabels():array
    {
        return [
            'name'=>'Название',
            'percent'=>'Процент',
            'is_depend'=>'Зависимый?',
            'defaultCost'=>'Цена по умолчанию',
            'status'=>'Статус',
        ];
    }
    /**
     * Вывел в статику получение label по аттрибуту
     * @throws \Exception
     */
    public static function getLabelByAttribute(string $attribute):?string
    {
        $result=ArrayHelper::getValue(self::getAttributeLabels(), $attribute);

        return $result??$attribute;
    }

    /**
     * Описание полей. Документация либо вывод на сайт.
     * @return string[]
     */
    public static function getAttributeDescriptions():array
    {
        return [
            'name'=>'Название услуги',
            'percent'=>'Если услуга зависимая, тогда указывается процент от зависимой позиции',
            'is_depend'=>'Данная услуга зависимая или нет. Например, если поставить галочку "монтаж" в заказе, тогда 
            зависимая услуга считает процент от стоимости позиции. Разрешена только одна зависимая услуга',
            'defaultCost'=>'Цена по умолчанию, устанавливается при добавлении услуги, после этого её можно изменить',
        ];
    }

    /**
     * Вывод описания по аттрибуту
     * @param string $attribute
     * @return string|null
     * @throws \Exception
     */
    public static function getDescriptionByAttribute(string $attribute):?string
    {
        $result=ArrayHelper::getValue(self::getAttributeDescriptions(), $attribute);
        return $result??'';
    }

    public static function isDependList(): array
    {
        return [
            0 => 'Не зависимый',
            1 => 'Зависимый',
        ];
    }
    public static function isDependName($isDepend): ?string
    {
        return ArrayHelper::getValue(self::isDependList(), $isDepend);
    }

    public static function statusList(): array
    {
        return [
           self::STATUS_ACTIVE => 'Активный',
           self::STATUS_NOT_ACTIVE => 'Не активный',
           self::STATUS_DELETED => 'Удаленный',
        ];
    }
    public static function statusListUser(): array
    {
        return [
           self::STATUS_ACTIVE => 'Активный',
           self::STATUS_NOT_ACTIVE => 'Не активный',
        ];
    }
    public static function statusName($status): ?string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public function onDelete():void
    {
        $this->status=self::STATUS_DELETED;
    }
    public function isDelete():bool
    {
        return $this->status==self::STATUS_DELETED;
    }

    public function beforeSave($insert)
    {
        if ($this->isDepend() and $service=self::getServiceIsDepend() and !$this->isIdEqualTo($service->id) ) {
            throw new \DomainException('Ошибка. Возможна только одна зависимая услуга');
        }

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
    public static function hasIsDepend():bool
    {
        $orders=Service::find()->andWhere(['is_depend'=>true])->count();
        return $orders > 0;
    }
    public static function getServiceIsDepend():?Service
    {
        return Service::find()->andWhere(['is_depend'=>true])->one();
    }

    private function isIdEqualTo(int $id):bool
    {
        return $this->id==$id;
    }

    private function isDepend():bool
    {
        return boolval($this->is_depend);
    }
}