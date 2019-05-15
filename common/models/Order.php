<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use yii\db\Query;

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
 * @property int $status_id
 * @property int $responsible_id
 *
 * @property User $autor
 * @property Client $client
 * @property User $lastChangeUser
 * @property Status $status
 * @property User $responsible
 * @property OrderCash[] $orderCashes
 * @property Cash[] $cashes
 * @property OrderProduct[] $orderProducts
 */
class Order extends \yii\db\ActiveRecord
{
    const NOPAID=0;         //не оплачен
    const FULLPAID=1;       //полностью
    const PARTPAID=2;       //частично
    const OVAERPAID=3;      //переплачен

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
            [['autor_id', 'lastChangeUser_id', 'client_id','status_id','responsible_id'], 'integer'],
            [['is_active','name','customer','address','description'], 'string'],
            [['cod'], 'string', 'max' => 20],
            [['autor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['autor_id' => 'id']],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['client_id' => 'id']],
            [['lastChangeUser_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['lastChangeUser_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status_id' => 'id']],
            [['responsible_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['responsible_id' => 'id']],
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
            'description' => Yii::t('app', 'Примечание'),
            'dateBegin' => Yii::t('app', 'Дата начала мероприятия'),
            'dateEnd' => Yii::t('app', 'Окончание'),
            'status_id' => Yii::t('app', 'Статус заказа'),
            'responsible_id' => Yii::t('app', 'Менеджер')

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
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResponsible()
    {
        return $this->hasOne(User::className(), ['id' => 'responsible_id']);
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
            $this->client_id = User::findOne(Yii::$app->user->id)->client_id;
            if ($this->isNewRecord) {
                $this->autor_id = Yii::$app->user->id;
                $this->created_at = date('Y-m-d H:i:s');
            }
            $this->updated_at = date('Y-m-d H:i:s');
            $this->lastChangeUser_id = Yii::$app->user->id;

            if (empty($this->dateBegin)) {
                $this->dateBegin = date('Y-m-d H:i:s');
            }
            if (empty($this->dateEnd)) {
                $this->dateEnd = date('Y-m-d H:i:s', strtotime($this->dateBegin . "+2 days"));
            }
            if (empty($this->status_id)) {
//              TODO: Брать значение по умолчанию из таблицы
                $this->status_id = 1;
            }
            $changeDateBegin=false;
            if ($this->dateBegin<>$this->getOldAttribute('dateBegin')) {
                $changeDateBegin=true;
            }
            $changeDateEnd=false;
            if ($this->dateBegin<>$this->getOldAttribute('dateEnd')) {
                $changeDateEnd=true;
            }
            $session = Yii::$app->session;
            if ($orderProducts = $this->orderProducts) {
                foreach ($orderProducts as $orderProduct)
                {
                    if ($changeDateBegin) {
                        $orderProduct->dateBegin=$this->dateBegin;
                    }

                    if ($changeDateEnd) {
                        $orderProduct->dateEnd=$this->dateEnd;
                    }

                    if ($orderProduct->check($this->status->action_id)===false) {
//                        $session->setFlash('error', 'Ошибка при сохранении заказа. У товара: '. $orderProduct->getName(). ' нет достаточного кол-ва на эти даты');
                        return false;
                    }
                }
            }


            return parent::beforeSave($insert);
        } else {
            return false;
        }
    }
    public function afterSave($insert, $changedAttributes)
    {
        if (empty($this->name)) {
            $this->name='Заказ №'.$this->id;
            $this->save();
        }
        if (key_exists('status_id',$changedAttributes)) {
            $oldStatus = Status::findOne($changedAttributes['status_id']);
            if (($this->status->action_id == Action::SOFTRENT) and ($oldStatus->action_id <> Action::SOFTRENT)) {
                if ($orderProducts = $this->orderProducts) {
                    foreach ($orderProducts as $orderProduct) {
                        $orderProduct->removeMovement(Action::HARDRENT);
                        $orderProduct->removeMovement(Action::UNHARDRENT);
                        $orderProduct->addMovement($this->status->action_id, null, null, false);
                    }
                }
            } else if (($this->status->action_id == Action::HARDRENT) and ($oldStatus->action_id <> Action::HARDRENT)) {
                if ($orderProducts = $this->orderProducts) {
                    foreach ($orderProducts as $orderProduct) {
                        $orderProduct->removeMovement(Action::SOFTRENT);
                        $orderProduct->removeMovement(Action::UNSOFTRENT);
                        $orderProduct->addMovement($this->status->action_id, null, null, false);
                    }
                }
            } else if ($this->status_id == Status::CANCELORDER) {
                //          Если статус заказан отменен, тогда освобождаем товары из резерва(брони)
                if ($orderProducts = $this->orderProducts) {
                    foreach ($orderProducts as $orderProduct) {
                        /* @var $orderProduct OrderProduct */
                        $orderProduct->removeMovement();
                    }
                }
            }
        }
        if (key_exists('dateBegin',$changedAttributes)) {
            if ($orderProducts=$this->orderProducts) {
                foreach ($orderProducts as $orderProduct) {
                    $orderProduct->dateBegin=$this->dateBegin;
                    if (!($orderProduct->save())) {
                        return false;
                    }
                }
            }
        }
        if (key_exists('dateEnd',$changedAttributes)) {
            if ($orderProducts=$this->orderProducts) {
                foreach ($orderProducts as $orderProduct) {
                    $orderProduct->dateEnd=$this->dateEnd;
                    if (!($orderProduct->save())) {
                        return false;
                    }
                }
            }
        }


////      Если поменялся аттрибут статус, тогда меняем все статусу у позиций заказа
//        if ((key_exists('status_id',$changedAttributes))and($this->status_id <> $changedAttributes['status_id'])) {
////          Если прошлый статус был старше удаляем его движения
//            if ($changedAttributes['status_id']) {
//                $oldStatus=Status::findOne($changedAttributes['status_id']);
//                if ($oldStatus->order > $this->status->order) {
//                    if ($orderProducts=$this->orderProducts) {
//                        foreach ( $orderProducts as $orderProduct) {
//                            /* @var $orderProduct OrderProduct*/
//                            if ($oldAction=Action::findOne($oldStatus->action_id)){
//                                if ($antipod_id = $oldStatus->action->antipod_id) {
//                                    $orderProduct->removeMovement($antipod_id);
//                                }
//                            }
//                        }
//                    }
//                }
//            }
//
////          Если надо менять товары по этому статусу, тогда меняем
//            if ($this->status->action_id) {
//                if ($orderProducts=$this->orderProducts) {
//                    foreach ( $orderProducts as $orderProduct) {
//                        /* @var $orderProduct OrderProduct*/
////                        $orderProduct->removeMovement($this->status->action_id);
//                        $orderProduct->addMovement($this->status->action_id);
////                        $orderProduct->save();
//                    }
//                }
//            }

//        }
        parent::afterSave($insert, $changedAttributes);

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
//    public static function getCurrent()
//    {
//        $session = Yii::$app->session;
//        if ($session['activeOrderId']) {
//            if ($current=Order::findOne($session['activeOrderId'])){
//                return $current;
//            }
//        } else {
//            $orders=self::getActual();
//            if ($current=reset($orders)) {
//                $session['activeOrderId']=$current->id;
//                return $current;
//            }
//        }
//        $current=new Order();
//        if ($current->save()) {
//            $session['activeOrderId']=$current->id;
//            return $current;
//        } else {
//            return false;
//        }
//    }
    /**
     * Добавляем товар в заказ. (мягкий резерв)
     *
     */

    public function addToBasket($productId,$qty,$type=OrderProduct::RENT,$orderBlock_id,$parent_id=null,$period=null,$dateBegin=null,$dateEnd=null)
    {
        $dateBegin=$dateBegin?$dateBegin:$this->dateBegin;
        $dateEnd=$dateEnd?$dateEnd:$this->dateEnd;
        //TODO: завязать на конфигурации или товаре миниальный период
        $period=$period?$period:1;

        $product=Product::findOne($productId);
        //      Проверяем наличие
        if (($qty<=0) and ($qty > $product->getBalance($dateBegin,$dateEnd))) {
            return "Не достаточно товаров на эти даты|".$product->getBalance($dateBegin,$dateEnd);
        };

        $orderProduct=new OrderProduct();
        $orderProduct->product_id=$productId;
        $orderProduct->order_id=$this->id;
        $orderProduct->qty=$qty;
        $orderProduct->type=$type;
        $orderProduct->orderBlock_id=$orderBlock_id;
        $orderProduct->parent_id=$parent_id?$parent_id:null;
        $orderProduct->name=empty($name)?null:$name;

        $orderProduct->dateBegin=$dateBegin;
        if ($type==OrderProduct::RENT) {
            $orderProduct->dateEnd=$dateEnd;
            $orderProduct->period=$period;
            $orderProduct->cost=$product->priceRent;
        } else {
            $orderProduct->cost=$product->priceSale;
        }

        return $orderProduct->save();
    }
    /**
     * Добавляем пустую(составную) позицю в зака
     */
    public function addEmptyToBasket ($orderBlock_id=null)
    {
        $orderProduct=new OrderProduct();
        $orderProduct->type=OrderProduct::COLLECT;
        $orderProduct->order_id=$this->id;
        $orderProduct->orderBlock_id=$orderBlock_id;
        $orderProduct->set=OrderProduct::getDefaultSet();
        $orderProduct->name=OrderProduct::getDefaultName();
        return $orderProduct->save();
    }


    /**
     * Возращаем массив позиций с разбивкой по блокам
     * Если параметр $orderBlockName не равен, тогда добавляем новый блок
     */
    public function getOrderProductsByBlock($orderBlock_id=null)
    {
        $aqOrderBlock=OrderBlock::find()->where(['order_id'=>$this->id]);
        if ($orderBlock_id) {
            $aqOrderBlock->andWhere(['id'=>$orderBlock_id]);
        }
        $orderBlocks=$aqOrderBlock->indexBy('id')->all();

        $respone=array();
        if (empty($orderBlocks)) {
            $orderBlock = new OrderBlock();
            $orderBlock->name = Block::getDefaultName();
            $orderBlock->order_id= $this->id;
            $orderBlock->save();
            $orderBlocks=$aqOrderBlock->indexBy('id')->all();
        }
        if ($orderBlocks) {
            foreach ($orderBlocks as $item) {
                // ArrayDataProvider
                $query= new Query;
                $dataProvider = new ArrayDataProvider([
                    'allModels' => $query->from('{{%order_product}}')->where(['orderBlock_id'=>$item->id])->groupBy('parent_id')->indexBy('id')->all(),
                    'pagination' => [ //постраничная разбивка
                        'pageSize' => 10, // 10 новостей на странице
                    ],
                ]);
                $respone[$item->id] = ['orderBlock' => $item, 'dataProvider' => $dataProvider];
            }
        } else {

        }

        return $respone;
    }

    public function getOrderBlock($orderBlock_id)
    {
//        $query=OrderProduct::find()
//            ->where(['order_id'=>$this->id])
//            ->andWhere(['orderBlock_id'=>$orderBlock_id])
//            ->indexBy('id');
        $query=OrderBlock::find()
            ->where(['id'=>$orderBlock_id])
            ->with(['orderProducts']);
        // ActiveDataProvider
        $dataProvider = new ActiveDataProvider([
            'pagination' => [
                'pageSize' => 10,
            ],
            'query' => $query
        ]);
//        return $dataProvider;
        return var_dump($query->all());
    }

    public function getDefaultBlock()
    {
        if (!($orderBlock=OrderBlock::find()->where(['order_id'=>$this->id])->orderBy('id')->one())){
            $orderBlock=new OrderBlock(['name'=>Block::getDefaultName(),'order_id'=>$this->id]);
            $orderBlock->save();
        }
        return $orderBlock;
    }

    private $_summ;
    public function getSumm()
    {
        if (empty($this->_summ)) {
            $this->_summ=-0;
            foreach ($this->orderProducts as $orderProduct) {
                $this->_summ+=$orderProduct->getSumm();
            }
        }
        return $this->_summ;
    }

    private $_paid;
    public function getPaid()
    {
        if (empty($this->_paid)) {
            $this->_paid=$this->getCashes()->sum('sum');
        }
        return $this->_paid;
    }

    private $_statusText;
    public function getStatusText ()
    {
        if (empty($this->_statusText)) {

            $status=$this->status->shortName;
            if ($orderProducts=$this->orderProducts) {

                foreach ($orderProducts as $orderProduct) {
                    if ($issue=$orderProduct->getBalance(Action::ISSUE)){
                        if ($return=$orderProduct->getBalance(Action::RETURN)){
                            if (($return+$issue)==0) {
                                $status = 'Завершен';
                            } else {
                                $status = 'Частично возращен';
                            }
                        } else {
                            $status=1;
                        }
                    }
                }
            }
        }
    }

    /**
     * Возращает стастус оплаты заказа
     * @param bool $text определяет в каком виде отдавать статус, по умолчанию выдает код статуса оплаты
     * @return int|string
     */
    public function getPaidStatus($text=false)
    {
        $status=$text?'Не оплачен':self::NOPAID;
        if ($this->getPaid()>0) {
            if ($this->getPaid()==$this->getSumm()) {
                $status=$text?'Полностью оплачен':self::FULLPAID;
            } else if ($this->getPaid()>$this->getSumm()) {
                $status=$text?'Переплачен':self::OVAERPAID;
            } else if ($this->getPaid()<$this->getSumm()) {
                $status=$text?'Частично оплачен':self::PARTPAID;
            }
        }
        return $status;
    }
}
