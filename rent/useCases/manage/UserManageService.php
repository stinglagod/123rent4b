<?php

namespace rent\useCases\manage;

use rent\entities\User\User;
use rent\forms\manage\User\UserCreateForm;
use rent\forms\manage\User\UserEditForm;
use rent\repositories\NotFoundException;
use rent\repositories\UserRepository;
use rent\services\newsletter\Newsletter;
use rent\services\RoleManager;
use rent\services\TransactionManager;
use rent\useCases\manage\Client\ClientManageService;

class UserManageService
{
    private $repository;
    private $roles;
    private $transaction;
    private ClientManageService $clientManageService;
    /**
     * @var Newsletter
     */
//    private $newsletter;

    public function __construct(
        UserRepository $repository,
        RoleManager $roles,
        TransactionManager $transaction,
        ClientManageService $clientManageService
//        Newsletter $newsletter
    )
    {
        $this->repository = $repository;
        $this->roles = $roles;
        $this->transaction = $transaction;
        $this->clientManageService = $clientManageService;
//        $this->newsletter = $newsletter;
    }

    public function create(UserCreateForm $form): User
    {
        $user = User::create(
            $form->name,
            $form->email,
            $form->password
        )
        ;
        $this->transaction->wrap(function () use ($user, $form) {
            $this->repository->save($user);
            $this->roles->assign($user->id, $form->role);
            if ($form->avatar) {
                $user->setAvatar($form->avatar);
            }
//            $this->newsletter->subscribe($user->email);
        });
        return $user;
    }

    public function edit($id, UserEditForm $form): void
    {
        $user = $this->repository->get($id);
        $user->edit(
            $form->name,
            $form->email,
            $form->surname,
            $form->patronymic,
            $form->telephone,
            $form->default_site,
            $form->default_client_id
        );
        $this->transaction->wrap(function () use ($user, $form) {
            if ($form->avatar) {
                $user->setAvatar($form->avatar);
            }
            $this->repository->save($user);
            $this->roles->assign($user->id, $form->role);

        });

    }

    public function assignRole($id, $role): void
    {
        $user = $this->repository->get($id);
        $this->roles->assign($user->id, $role);
    }

    public function remove($id): void
    {
        $user = $this->repository->get($id);
        //Проверяем является ли пользователь владельцем компании. Если является тогда удалить не можем
        if ($user->isOwner()) {
            throw new \DomainException('Нельзя удалить пользователя т.к. он является владельцем компании.');
        }
        $this->transaction->wrap(function () use ($user,$id){
            //отключаем у пользователя компании
            foreach ($user->clients as $client) {
                $this->clientManageService->removeUser($client->id,$id);
            }

            //удаляем пользователя
            $this->repository->remove($user);
            //очищаем права
            $authManager = \Yii::$app->get('authManager');
            $authManager->revokeAll($id);
        });

    }


    /**
     * Устанавливаем роль для пользователя
     */
    protected function setRole(int $id, array $roles)
    {
        if(!empty( $roles ))
        {
            /** @var \yii\rbac\DbManager $authManager */

            $authManager = \Yii::$app->authManager;
            $authManager->revokeAll($id);

            foreach ($roles as $item)
            {
                $r = $authManager->createRole($item);
                $authManager->assign($r,$id);
            }
        }
        else
        {
            throw new NotFoundException('Bad Request.');
        }

    }
}