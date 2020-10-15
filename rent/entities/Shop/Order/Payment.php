<?php

namespace rent\entities\Shop\Order;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use rent\entities\Client\Site;
use rent\entities\User\User;
use rent\entities\Shop\Order\DeliveryData;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use rent\entities\behaviors\ClientBehavior;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * @property int $id
 * @property int $dateTime
 * @property int $order_id
 * @property int $type_id
 * @property float $sum
 * @property float $sumWithSign
 * @property int $responsible_id
 * @property string $responsible_name
 * @property int $site_id
 * @property int $active
 * @property string $payer_name
 * @property int $payer_id
 * @property int $purpose_id
 * @property int $sign
 * @property string $note
 * @property CustomerData $payerData
 *
 * @property Site $site
 * @property User $responsible
 * @property Order $order
 * @property BalanceCash[] $balancesCash
 */
class Payment extends ActiveRecord
{
    const TYPE_BY_CARD = 1;             //оплата на карту
    const TYPE_CASH = 2;                //оплата наличными
    const TYPE_TO_BANK_ACCOUNT = 3;     //оплата на расчетный счет

    /** PoP  Purpose of Payment  */
    const POP_INCOMING = 1;             //Приход
    const POP_ADVANCE = 2;              //Аванс
    const POP_DEPOSIT = 3;              //Залог
    const POP_REFUND = 4;               //Возрат денег
    const POP_CONTRACTOR = 5;           //Платеж контрагенту

    public $payerData;

    public static function create(
        int $dateTime,
        int $type_id,
        float $sum,
        $responsible_id,
        $responsible_name,
        $payer_id,
        CustomerData $payerData,
        $purpose_id,
        $note
    ):self
    {
        $payment = new static();
        $payment->dateTime = $dateTime;
        $payment->type_id = $type_id;
        $payment->sum = $sum;
        $payment->responsible_id = $responsible_id?:null;
        $payment->responsible_name = $responsible_name;
        $payment->payer_id = $payer_id?:null;
        $payment->payerData=$payerData;
        $payment->purpose_id = $purpose_id;
        $payment->note = $note;

        $payment->active=1;
        return $payment;
    }

    public function isActive():bool
    {
        return $this->active==true;
    }

    public function getSign():int
    {
        if (($this->purpose_id==self::POP_REFUND) or
            ($this->purpose_id==self::POP_CONTRACTOR))
            return -1;
        else
            return 1;
    }

    public function isPlus():int
    {
        return self::getSign()==1;
    }

    public function isIdEqualTo($id):bool
    {
        return $this->id == $id;
    }
    public function getSumWithSign():float
    {
        return $this->sum*$this->sign;
    }

    #############################################

    public function getResponsible(): ActiveQuery
    {
        return $this->hasMany(User::class, ['id' => 'user_id']);
    }
    public function getSite(): ActiveQuery
    {
        return $this->hasMany(Site::class, ['id' => 'site_id']);
    }
    public function getOrder(): ActiveQuery
    {
        return $this->hasMany(Order::class, ['id' => 'order_id']);
    }
    public function getBalancesCash(): ActiveQuery
    {
        return $this->hasMany(BalanceCash::class, ['payment_id' => 'id']);
    }
    ##########################################

    public static function tableName(): string
    {
        return '{{%shop_payments}}';
    }

    public function behaviors(): array
    {
        return [
            ClientBehavior::class,
            TimestampBehavior::class,
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['balancesCash'],
            ],
        ];
    }

    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public function afterFind(): void
    {

        $this->payerData = new CustomerData(
            $this->getAttribute('payer_phone'),
            $this->getAttribute('payer_name'),
            $this->getAttribute('payer_email')
        );
        parent::afterFind();
    }

    public function beforeSave($insert): bool
    {
        $this->setAttribute('payer_phone', $this->payerData->phone);
        $this->setAttribute('payer_name', $this->payerData->name);
        $this->setAttribute('payer_email', $this->payerData->email);

        if ($this->isActive()) {
            $balancesCash=$this->balancesCash;
            $balancesCash[]=BalanceCash::create($this->dateTime,$this->sum*$this->sign);
            $this->balancesCash=$balancesCash;
        } else {
            $this->balancesCash=[];
        }
        return parent::beforeSave($insert);
    }

    public static function find()
    {
        return parent::find()->where(['site_id' => Yii::$app->params['siteId']]);
    }
    public function attributeLabels()
    {
        return [
            'dateTime' => 'Дата',
            'type_id' => 'Тип',
            'purpose_id' => 'Назначение',
            'sum'=>'Сумма',
            'note'=>'Примечание',
        ];
    }
}