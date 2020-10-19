<?php

namespace rent\services\manage;

use rent\entities\User\User;
use rent\forms\manage\User\UserCreateForm;
use rent\forms\manage\User\UserEditForm;
use rent\repositories\NotFoundException;
use rent\repositories\UserRepository;

class UserManageService
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(UserCreateForm $form): User
    {
        $user = User::create(
            $form->name,
            $form->email,
            $form->password
        )
        ;

        $this->repository->save($user);
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
        if ($form->avatar) {
            $user->setAvatar($form->avatar);
        }
        if ($form->role) {
            $this->setRole($user->id,$form->role);
        }
        $this->repository->save($user);
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