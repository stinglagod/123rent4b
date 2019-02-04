<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property int $id
 * @property string $cod
 * @property string $created_at
 * @property string $updated_at
 * @property int $autor_id
 * @property int $lastChangeUser_id
 * @property string $is_active
 * @property int $client_id
 * @property string $name
 * @property string $customer
 * @property string $address
 * @property string $description
 * @property string $dateBegin
 * @property string $dateEnd
 *
 * @property User $autor
 * @property Client $client
 * @property User $lastChangeUser
 * @property OrderCash[] $orderCashes
 * @property Cash[] $cashes
 * @property OrderProduct[] $orderProducts
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at','dateBegin','dateEnd'], 'safe'],
            [['autor_id', 'lastChangeUser_id', 'client_id'], 'integer'],
            [['is_active','name','customer','address','description'], 'string'],
            [['cod'], 'string', 'max' => 20],
            [['autor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['autor_id' => 'id']],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['client_id' => 'id']],
            [['lastChangeUser_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['lastChangeUser_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'cod' => Yii::t('app', 'Cod'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'autor_id' => Yii::t('app', 'Autor ID'),
            'lastChangeUser_id' => Yii::t('app', 'Last Change User ID'),
            'is_active' => Yii::t('app', 'Is Active'),
            'client_id' => Yii::t('app', 'Client ID'),
            'name' => Yii::t('app', 'Имя заказа'),
            'customer' => Yii::t('app', 'Имя заказчика'),
            'address' => Yii::t('app', 'Адрес'),
            'description' => Yii::t('app', 'Описание'),
            'dateBegin' => Yii::t('app', 'Дата начала'),
            'dateEnd' => Yii::t('app', 'Окончание'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAutor()
    {
        return $this->hasOne(User::className(), ['id' => 'autor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastChangeUser()
    {
        return $this->hasOne(User::className(), ['id' => 'lastChangeUser_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderCashes()
    {
        return $this->hasMany(OrderCash::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCashes()
    {
        return $this->hasMany(Cash::className(), ['id' => 'cash_id'])->viaTable('{{%order_cash}}', ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderProducts()
    {
        return $this->hasMany(OrderProduct::className(), ['order_id' => 'id']);
    }

    /**
     * возращает актуальный заказ у пользователя, если его нет возращает false
     * @return \yii\db\ActiveQuery
     */
    static public function getActual()
    {
        $orders=self::find()->where(['autor_id'=>Yii::$app->user->id])->indexBy('id')->all();
        if (empty($orders)) {
            $orders= new Order();
            $orders->save();
            $session = Yii::$app->session;
            unset($session['activeOrderId']);
            return [$orders];
        }
        return $orders;
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->client_id=User::findOne(Yii::$app->user->id)->client_id;
            if ($this->isNewRecord) {
                $this->autor_id=Yii::$app->user->id;
                $this->created_at=date('Y-m-d H:i:s');
            }
            $this->updated_at=date('Y-m-d H:i:s');
            $this->lastChangeUser_id=Yii::$app->user->id;


            return parent::beforeSave($insert);
        } else {
            return false;
        }
    }
    public function afterSave($insert, $changedAttributes)
    {

        parent::afterSave($insert, $changedAttributes);
        if (empty($this->name)) {
            $this->name='Заказ №'.$this->id;
        }
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(OrderProduct::class, ['order_id' => 'id']);
    }

    /**
     * возращаем текущий активный заказ. Если его нет. берем первый. Если нет заказов создаем пустой заказ
     * @return Order
     */
    public static function getCurrent()
    {
        $session = Yii::$app->session;
        if ($session['activeOrderId']) {
            if ($current=Order::findOne($session['activeOrderId'])){
                return $current;
            }
        } else {
            $orders=self::getActual();
            if ($current=reset($orders)) {
                return $current;
            }
        }
        $current=new Order();
        if ($current->save()) {
            return $current;
        } else {
            return false;
        }
    }
    /**
     * Добавляем товар в заказ. (мягкий резерв)
     *
     */
    public function addToBasket($productId,$qty,$type=OrderProduct::RENT,$period=null,$dateBegin=null,$dateEnd=null)
    {
        $product=Product::findOne($productId);

        $dateBegin=$dateBegin?$dateBegin:$this->dateBegin;
        $dateBegin=$dateEnd?$dateEnd:$this->dateEnd;
        //TODO: завязать на конфигурации или товаре миниальный период
        $period=$period?$period:1;
//      проверить наличие на эти даты

        $orderProduct=OrderProduct::find()->where(['order_id'=>$this->id,'product_id'=>$productId,'dateBegin'=>$dateBegin]);

        if ($type==OrderProduct::RENT) {
            $orderProduct->andWhere(['type'=>$type])->andWhere(['dateEnd'=>$dateEnd]);
        } elseif ($type==OrderProduct::SALE) {
            $orderProduct->andWhere(['type'=>$type]);
        } elseif ($type==OrderProduct::SERVICE) {
            $orderProduct->andWhere(['type'=>$type]);
        }

        if ($orderProduct=$orderProduct->one()) {
            $orderProduct->qty+=$qty;
        } else {
            $orderProduct=new OrderProduct();
            $orderProduct->product_id=$productId;
            $orderProduct->order_id=$this->id;
            $orderProduct->qty=$qty;

            $orderProduct->dateBegin=$dateBegin;
            if ($type==OrderProduct::RENT) {
                $orderProduct->dateEnd=$dateEnd;
                $orderProduct->period=$period;
                $orderProduct->cost=$product->priceRent;
            } else {
                $orderProduct->cost=$product->priceSale;
            }
        }
        return $orderProduct->save();
    }
}
