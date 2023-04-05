<?php

namespace rent\entities\Support\Task;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use rent\entities\behaviors\ClientBehavior;
use rent\entities\Client\Client;
use rent\entities\Client\Site;
use rent\entities\Support\Task\Comment;
use rent\entities\User\User;
use unit\forms\auth\AdminSignupFormTest;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $name                           //имя тикета
 * @property string $text                           //текст тикета
 * @property integer $responsible_id                //Ответственный
 * @property string $responsible_name
 * @property integer $customer_id                   //Инициатор
 * @property string $customer_name
 * @property integer $status                        //Статус
 * @property integer $type                          //Тип
 * @property integer $is_completed                  //Выполнена
 * @property string $commentClosed                  //Комментарий почему закрыта, но не выполнена
 * @property integer $priority                      //Приоритет
 *
 * @property integer $site_id
 * @property integer $client_id
 * @property string $client_name
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $author_id
 * @property integer $lastChangeUser_id
 *
 * @property \rent\entities\Client\Site $site
 * @property Comment[] $comments
 *
 */
class Task extends ActiveRecord
{
    const PREFIX_DEFAULT_NAME='Задача';
    const STATUS_NEW=1;                 //Новая заявка
    const STATUS_IN_WORK=5;             //В работе
    const STATUS_CLOSED=10;             //Закрыта
    const STATUS_DELETED=15;            //Удалена

    const TYPE_BUG=1;                   //Ошибка
    const TYPE_PROPOSAL=5;              //Предложение
    const TYPE_ENHANCEMENT=10;          //Улучшение

    public static function create(User $customer, string $text, Client $client, int $type):self
    {
        return $entity=new self([
            'text'=>$text,
            'customer_id'=>$customer->id,
            'customer_name'=>$customer->getShortName(),
            'client_id'=>$client->id,
            'client_name'=>$client->name,
            'status'=>self::STATUS_NEW,
            'type'=>$type
        ]);
    }

    public function changeResponsible(User $responsible):void
    {
        $this->responsible_id=$responsible->id;
        $this->responsible_name=$responsible->getShortName();
    }
    public function changeType(int $type):void
    {
        $this->type=$type;
    }
    public function onInWork():void
    {
        $this->status=self::STATUS_IN_WORK;
    }
    public function onClose(bool $isCompleted=true,string $commentClosed=null):void
    {
        $this->status=self::STATUS_CLOSED;
        $this->is_completed=$isCompleted;
        $this->commentClosed=$commentClosed;
        if ($isCompleted===false) {
            if (empty($commentClosed)) {
                throw new \RuntimeException('Ошибка! Нельзя закрыть не выполненную заявку без причины закрытия');
            }
        }
    }
    public function onDelete():void
    {
        $this->status=self::STATUS_DELETED;
    }

#Comment
    public function addComment(string $message,User $author)
    {
        $comment=Comment::create($message,$author);
        $this->comments[]=$comment;
    }
    public function getClient() :ActiveQuery
    {
        return $this->hasOne(Site::class, ['id' => 'client_id']);
    }

    public function getSite() :ActiveQuery
    {
        return $this->hasOne(Site::class, ['id' => 'site_id']);
    }

    public function getComments():ActiveQuery
    {
        return $this->hasMany(Comment::class, ['task_id' => 'id']);
    }

    public static function tableName(): string
    {
        return '{{%support_tickets}}';
    }

    public function behaviors(): array
    {
        return [
            ClientBehavior::class,
            TimestampBehavior::class,
            'SaveRelationsBehavior'=>
                [
                    'class' => SaveRelationsBehavior::class,
                    'relations' => [
                        'comments',
                    ],
                ],
        ];
    }
    ###
//    private function getDefaultName():string
//    {
//        return self::PREFIX_DEFAULT_NAME . ' №: ' .$this->id;
//    }
}