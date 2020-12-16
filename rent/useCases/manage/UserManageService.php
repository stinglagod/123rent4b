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

class UserManageService
{
    private $repository;
    private $roles;
    private $transaction;
    /**
     * @var Newsletter
     */
//    private $newsletter;

    public function __construct(
        UserRepository $repository,
        RoleManager $roles,
        TransactionManager $transaction
//        Newsletter $newsletter
    )
    {
        $this->repository = $repository;
        $this->roles = $roles;
        $this->transaction = $transaction;
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
            $form->default_site
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
        $this->repository->remove($user);
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