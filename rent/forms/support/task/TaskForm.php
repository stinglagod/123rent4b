<?php
namespace rent\forms\support\task;
use rent\entities\Client\Client;
use rent\entities\Support\Task\Task;
use rent\entities\User\User;
use rent\forms\CompositeForm;
use yii\helpers\ArrayHelper;

/**
 * @property CommentForm $commentForm
 */
class TaskForm extends CompositeForm
{
    public ?string $name=null;
    public ?string $commentClosed=null;
    public ?string $text=null;
    public ?int $responsible_id=null;
    public ?int $customer_id=null;
    public ?int $status=null;
    public ?int $type=null;
    public ?int $priority=null;
    public ?int $site_id=null;
    public ?int $client_id=null;
    public ?bool $is_completed=null;
    public ?Task $_task=null;

    public function __construct(?Task $task=null,$config = [])
    {
        parent::__construct($config);
        if ($task) {
            $this->name=$task->name;
            $this->commentClosed=$task->commentClosed;
            $this->text=$task->text;
            $this->responsible_id=$task->responsible_id;
            $this->customer_id=$task->customer_id;
            $this->status=$task->status;
            $this->priority=$task->priority;
            $this->type=$task->type;
            $this->site_id=$task->site_id;
            $this->client_id=$task->client_id;
            $this->is_completed=$task->is_completed;

            $this->_task = $task;
        } else {
            $this->status = Task::STATUS_NEW;
            $this->responsible_id=\Yii::$app->user->id;
            $this->customer_id=\Yii::$app->user->id;
        }


    }

    public function isNew():bool
    {
        return empty($this->_task);
    }

    private $_clientList=[];
    public function getClientsList(): array
    {
        if (empty($this->_clientList)) {
            $this->_clientList=ArrayHelper::map(Client::find()->orderBy('name')->all(), 'id', 'name');
        }
        return $this->_clientList;
    }
    public function getClientName(int $id):string
    {
        $name=ArrayHelper::getValue($this->getClientsList(),$id);
        return $name??'';
    }
    private $_responsibleList=[];
    public function getResponsibleList(): array
    {
        if (empty($this->_responsibleList)) {
            $this->_responsibleList=User::getResponsibleList();
        }
        return $this->_responsibleList;
    }
    public function getResponsibleName(int $id):string
    {
        $name=ArrayHelper::getValue($this->getResponsibleList(),$id);
        return $name??'';
    }
    private $_statusList=[];
    public function getStatusList(): array
    {
        if (empty($this->_statusList)) {
            $this->_statusList=Task::getStatusLabels();
        }
        return $this->_statusList;
    }
    public function getStatusName(int $id):string
    {
        $name=ArrayHelper::getValue($this->getStatusList(),$id);
        return $name??'';
    }
    public function rules(): array
    {
        return [
            [['name','commentClosed'], 'string'],
            [['text'], 'string'],
            [['responsible_id', 'customer_id','status','type','priority','site_id','client_id'], 'integer'],
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
            [['status',], 'integer'],
            [['status',],'default', 'value'=> Task::STATUS_NEW],
            ['status', 'in', 'range' => array_keys(Task::getStatusLabels())],
            [['is_completed',], 'boolean'],
            [['name','text','status','priority'], 'required'],
        ];
    }

    /**
     * Иногда нам надо отдать значение не как в базе идентификатор, а человечески понятное значение.
     * Например не client_id, а имя клиента, не type, а название типа
     * @param $attributeName
     */
    public function getValue($attributeName)
    {
        switch ($attributeName) {
            case 'type':
                return Task::getTypeLabel($this->type);
            case 'priority':
                return Task::getPriorityLabel($this->priority);
            case 'client_id':
                return $this->getClientName($this->client_id);
            case 'responsible_id':
                return $this->getResponsibleName($this->responsible_id);
            case 'status':
                return $this->getStatusName($this->status);
            default :
                return $this->$attributeName;
        }
    }

    protected function internalForms(): array
    {
        return ['commentForm'];
    }
//    public function attributeLabels()
//    {
//        return [
//            'text' => 'Поисковый запрос',
//            'category' => 'Категория',
//            'on_site' => 'Опубликованы',
//            'site' => 'Сайт'
//        ];
//    }
    public function attributeLabels()
    {
        return Task::getAttributeLabels();
    }
}
