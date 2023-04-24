<?php

namespace rent\useCases\support;

use rent\entities\Support\Task\Comment;
use rent\entities\Support\Task\Task;
use rent\forms\support\task\CommentForm;
use rent\forms\support\task\TaskForm;
use rent\repositories\Client\ClientRepository;
use rent\repositories\support\TaskRepository;
use rent\repositories\UserRepository;

class SupportService
{
    private TaskRepository $taskRepository;
    private ClientRepository $clientRepository;
    private UserRepository $userRepository;

    public function __construct(TaskRepository $taskRepository,ClientRepository $clientRepository,UserRepository $userRepository)
    {
        $this->clientRepository=$clientRepository;
        $this->taskRepository=$taskRepository;
        $this->userRepository = $userRepository;
    }

    public function createTask(TaskForm $taskForm,$clientOrId=null):Task
    {
        if ((\Yii::$app->user->can('super_admin'))and (empty($clientOrId))) {
            $clientOrId = $taskForm->client_id;
        } else {
            $clientOrId = \Yii::$app->settings->getClientId();
        }

        $client=$clientOrId?$this->clientRepository->get($clientOrId):\Yii::$app->settings->getClient() ;
        $customer=$this->userRepository->get($taskForm->customer_id);
        $responsible=$this->userRepository->find($taskForm->responsible_id);
        $task=Task::create(
            $taskForm->name,
            $customer,
            $taskForm->text,
            $client,
            $taskForm->type,
            $responsible,
            $taskForm->priority,
        );
        $this->taskRepository->save($task);
        return $task;
    }

    public function editTask($taskOrId,TaskForm $taskForm):void
    {
        $task=$this->taskRepository->get($taskOrId);
        $responsible=$this->userRepository->find($taskForm->responsible_id);
        $client=$taskForm->client_id?$this->clientRepository->get($taskForm->client_id):\Yii::$app->settings->getClient() ;
        $task->edit(
            $taskForm->name,
            $taskForm->text,
            $taskForm->type,
            $taskForm->status,
            $responsible,
            $taskForm->priority,
            $client
        );
        $this->taskRepository->save($task);
    }
    public function addComment($taskOrId,CommentForm $commentForm):Comment
    {
        $task=$this->taskRepository->get($taskOrId);

        //проверяем можем ли мы добавлять комментарии
        if (!$task->canAddComment()) {
            throw new \DomainException('Невозможно добавить комментарий.');
        }
        $authorId=$commentForm->author_id??\Yii::$app->user->id;
        $comment=$task->addComment($commentForm->message,$this->userRepository->get($authorId));

        //Если есть файлы загружаем файлы
        foreach ($commentForm->files->files as $file) {
            $comment->addFile($file);
        }

        /**
         * Меняем статус в зависимости от автора комментария.
         * Если автор ответственный за задачу, тогда статус устанавливаем: Task::STATUS_WAITING_RESPONSE
         * Если автор НЕ ответственный за задачу и Задача не новая, тогда ставим:
         * Task::STATUS_SEND_RESPONSE
         **/
        if ($task->isResponsible($authorId)) {
            if ($commentForm->waitingResponse) {
                $task->onWaitingResponse();
            } else {
                $task->onInWork();
            }

        } else {
            if (!$task->isStatusNew()) {
                $task->onSendResponse();
            }
        }

        $this->taskRepository->save($task);
        return $comment;
    }
    public function closeTask($taskOrId,bool $isCompleted, ?string $commentClosed='')
    {
        $task=$this->taskRepository->get($taskOrId);
        $task->onClose($isCompleted,$commentClosed);
        $this->taskRepository->save($task);
    }
    public function deleteTask($taskOrId)
    {
        $task=$this->taskRepository->get($taskOrId);
        $task->onDelete();
        $this->taskRepository->save($task);
    }
}