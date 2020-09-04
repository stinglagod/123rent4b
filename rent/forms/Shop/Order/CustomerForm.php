<?php

namespace rent\forms\Shop\Order;

use rent\entities\User\User;
use yii\base\Model;
use Yii;

class CustomerForm extends Model
{
    public $phone;
    public $name;

    public function __construct( array $config = [])
    {
        $user=User::findOne(Yii::$app->user->id);
        $this->phone = $user->telephone?:null;
        $this->name=$user->name;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['phone', 'name'], 'required'],
            [['phone', 'name'], 'string', 'max' => 255],
        ];
    }
}