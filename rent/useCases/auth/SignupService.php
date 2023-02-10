<?php

namespace rent\useCases\auth;

use rent\entities\Client\Client;
use rent\entities\Shop\Category\Category;
use rent\entities\User\User;
use rent\forms\auth\AdminSignupForm;
use rent\forms\auth\SignupForm;
use rent\forms\manage\Client\ClientCreateForm;
use rent\repositories\Client\ClientRepository;
use rent\repositories\UserRepository;
use rent\useCases\manage\Client\ClientManageService;
use yii\mail\MailerInterface;
use rent\services\TransactionManager;
use rent\services\RoleManager;

class SignupService
{
    private MailerInterface $mailer;
    private UserRepository $users;
    private TransactionManager $transaction;
    private RoleManager $roles;
    private ClientRepository $clients;
    private ClientManageService $clientManageService;

    public function __construct(
        UserRepository $users,
        MailerInterface $mailer,
        TransactionManager $transaction,
        RoleManager $roles,
        ClientRepository $clients,
        ClientManageService $clientManageService
    )
    {
        $this->mailer = $mailer;
        $this->users = $users;
        $this->transaction = $transaction;
        $this->roles = $roles;
        $this->clients = $clients;
        $this->clientManageService = $clientManageService;
    }

    public function signup(SignupForm $form): void
    {
        $user = User::requestSignup(
            $form->name,
            $form->surname,
            $form->email,
            $form->password
        );
        $this->transaction->wrap(function () use ($user, $form) {
            $this->users->save($user);
            $this->roles->assign($user->id, User::DEFAULT_ROLE);
        });

        $sent = $this->mailer
            ->compose(
                ['html' => 'auth/signup/confirm-html', 'text' => 'auth/signup/confirm-text'],
                ['user' => $user]
            )
            ->setTo($form->email)
            ->setSubject('Подтверждение регистрации на сайте ' . \Yii::$app->name)
            ->send();
        if (!$sent) {
            throw new \RuntimeException('Email sending error.');
        }
    }

    public function adminSignup(AdminSignupForm $form): void
    {
        if ($user = $this->users->findByUsernameOrEmail($form->email)) {
            throw new \DomainException('Пользователь с email: '. $form->email . ' уже зарегистрирован');
        }
        $user= User::requestSignup($form->name,'',$form->email,$form->password);


        $this->transaction->wrap(function () use ($user, $form) {

            $client=$this->clientManageService->create($form->client);
            //сохраняем пользователя
            $this->users->save($user);
            //добавляем роль админа
            $this->roles->assign($user->id, User::ROLE_ADMIN);
            //Делаем пользователя владельцем компании
            $client->assignUser($user->id,true);
            $this->clients->save($client);
            //Назначаем пользователю компанию по умолчанию
            $user->default_client_id=$client->id;
            $this->users->save($user);
        });

        $sent = $this->mailer
            ->compose(
                ['html' => 'auth/signup/confirmAdmin-html', 'text' => 'auth/signup/confirmAdmin-text'],
                ['user' => $user]
            )
            ->setTo($form->email)
            ->setSubject('Подтверждение регистрации на сайте ' . \Yii::$app->name)
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Email sending error.');
        }
    }

    public function confirm($token): void
    {
        if (empty($token)) {
            throw new \DomainException('Empty confirm token.');
        }
        $user = $this->users->getByEmailConfirmToken($token);
        $user->confirmSignup();
        $this->users->save($user);
    }
}