<?php

namespace rent\useCases\auth;

use rent\entities\Client\Client;
use rent\entities\User\User;
use rent\forms\auth\AdminSignupForm;
use rent\forms\auth\SignupForm;
use rent\repositories\Client\ClientRepository;
use rent\repositories\UserRepository;
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

    public function __construct(
        UserRepository $users,
        MailerInterface $mailer,
        TransactionManager $transaction,
        RoleManager $roles,
        ClientRepository $clients
    )
    {
        $this->mailer = $mailer;
        $this->users = $users;
        $this->transaction = $transaction;
        $this->roles = $roles;
        $this->clients = $clients;
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
        $client=Client::create($form->client->name,$form->client->status);

        $this->transaction->wrap(function () use ($user, $form,$client) {
            $this->users->save($user);
            $this->roles->assign($user->id, User::ROLE_ADMIN);
            $client->assignUser($user->id,true);
            $this->clients->save($client);

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