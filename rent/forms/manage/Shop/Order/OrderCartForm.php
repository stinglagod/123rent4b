<?php
namespace rent\forms\manage\Shop\Order;

use rent\entities\Shop\Order\Item\OrderItem;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use rent\entities\Shop\Order\Order;
use rent\entities\User\User;
use rent\forms\CompositeForm;
use Yii;

/**
 * @property integer $order_id
 * @property integer $block_id
 * @property integer $collect_id
 * @property integer $date_begin
 * @property integer $date_end
 * @property integer $qty
 * @property float $cost
 *
 *
 * @property CustomerForm $customer
 * @property DeliveryForm $delivery
 *
 */
class OrderCartForm extends Model
{

    public $order_id;
    public $block_id;
    public $collect_id;
    public $date_begin;
    public $date_end;
    public $qty;
    public $cost;

    public function __construct($order_id=null,$block_id=null,$collect_id=null,$config = [])
    {
        if ($order_id) {
            Yii::$app->session->set('order_id',$order_id);
        }
        if ($block_id) {
            Yii::$app->session->set('block_id',$block_id);
        }
        if ($collect_id) {
            Yii::$app->session->set('collect_id',$collect_id);
        }

        $this->order_id = (Yii::$app->session->get('order_id'))?:null;
        $this->block_id = (Yii::$app->session->get('block_id'))?:null;
        $this->collect_id = (Yii::$app->session->get('collect_id'))?:null;
        parent::__construct($config);
    }

    public function ordersList(): array
    {
        return ArrayHelper::map(Order::find()->orderBy('date_begin')->asArray()->all(), 'id','name');
    }

    public function blocksList(): array
    {
        if ($this->order_id)
            return ArrayHelper::merge([0=>'Выберите ...'],ArrayHelper::map(OrderItem::find()->where(['order_id'=>$this->order_id,'type_id'=>OrderItem::TYPE_BLOCK])->orderBy('sort')->asArray()->all(), 'id','name'));
        return [];
    }
    public function collectList(): array
    {
        if ($this->block_id)
            return ArrayHelper::merge([0=>'Выберите ...'],ArrayHelper::map(OrderItem::find()->where(['parent_id'=>$this->block_id,'type_id'=>OrderItem::TYPE_COLLECT])->orderBy('sort')->asArray()->all(), 'id','name'));
        return [];
    }

    public function rules(): array
    {
        return [
            [['order_id',], 'required'],
            [['order_id','block_id','collect_id','date_begin','date_end','qty'], 'integer'],
        ];
    }
}