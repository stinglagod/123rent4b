<?php

namespace common\models;

use Yii;
use creocoder\nestedsets\NestedSetsQueryBehavior;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property int $id
 * @property int $tree
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 * @property string $name
 * @property int $client_id
 *
 * @property Client $client
 */
class CategoryQuery extends \yii\db\ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}
