<?php
namespace rent\forms\support\task;
use rent\entities\User\User;
use rent\forms\CompositeForm;
/**
 * @property CommentForm $commentForm
 */
class TaskForm extends CompositeForm
{
    public function rules(): array
    {
        return [
            [['name','commentClosed'], 'string'],
            [['text'], 'string'],
            [['responsible_id', 'customer_id','status','type','priority','site_id','client_id','created_at',
                'updated_at','author_id','lastChangeUser_id'], 'integer'],
            ['responsible_id', 'exist',
                'targetClass' => '\rent\entities\User\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'targetAttribute' => ['responsible_id' => 'id'],
                'message' => 'Такого пользователя не существует, либо он не активен'
            ],
            ['customer_id', 'exist',
                'targetClass' => '\rent\entities\User\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'targetAttribute' => ['customer_id' => 'id'],
                'message' => 'Такого пользователя не существует, либо он не активен'
            ],
            ['author_id', 'exist',
                'targetClass' => '\rent\entities\User\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'targetAttribute' => ['author_id' => 'id'],
                'message' => 'Такого пользователя не существует, либо он не активен'
            ],
            ['lastChangeUser_id', 'exist',
                'targetClass' => '\rent\entities\User\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'targetAttribute' => ['lastChangeUser_id' => 'id'],
                'message' => 'Такого пользователя не существует, либо он не активен'
            ],
            [['status',], 'integer'],
            ['status', 'in', 'range' => [\rent\entities\Client\Client::STATUS_ACTIVE, \rent\entities\Client\Client::STATUS_DELETED, \rent\entities\Client\Client::STATUS_NOT_ACTIVE]],
            [['is_completed',], 'boolean'],
            [['name','text','responsible_id','customer_id','status','priority','client_id','author_id'], 'required'],
        ];
    }
    protected function internalForms(): array
    {
        return ['commentForm'];
    }
    public function attributeLabels()
    {
        return [
            'text' => 'Поисковый запрос',
            'category' => 'Категория',
            'on_site' => 'Опубликованы',
            'site' => 'Сайт'
        ];
    }
}
