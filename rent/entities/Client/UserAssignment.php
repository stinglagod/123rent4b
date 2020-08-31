<?php

namespace rent\entities\Client;

use rent\entities\Client\Client;
use rent\entities\User\User;
use Yii;

/**
 * This is the model class for table "{{%client_user_assignments}}".
 *
 * @property int $client_id
 * @property int $user_id
 * @property boolean $owner
 *
 */
class UserAssignment extends \yii\db\ActiveRecord
{
    public static function create($userId): self
    {
        $assignment = new static();
        $assignment->user_id = $userId;
        return $assignment;
    }

    public function isForUser($id): bool
    {
        return $this->user_id == $id;
    }
    public static function tableName()
    {
        return '{{%client_user_assignments}}';
    }


}
