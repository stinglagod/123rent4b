<?php

namespace rent\entities\Support\Ticket;

use rent\entities\behaviors\ClientBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Log extends ActiveRecord
{

    public static function tableName(): string
    {
        return '{{%support_tickets_log}}';
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class
        ];
    }
}