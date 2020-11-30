<?php

namespace rent\useCases\auth;

use rent\entities\User\User;
use rent\forms\auth\SignupForm;
use rent\repositories\UserRepository;
use yii\mail\MailerInterface;
use rent\services\TransactionManager;
use rent\services\RoleManager;

class SignupService
{
    private $mailer;
    private $users;
    private $transaction;
    private $roles;

    public function __construct(UserRepository $users, MailerInterface $mailer, TransactionManager $transaction,RoleManager $roles)
    {
        $this->mailer = $mailer;
        $this->users = $users;
        $this->transaction = $transaction;
        $this->roles = $roles;
    }

    public function signup(SignupForm $form): void
    {
        $user = User::requestSignup(
            $form->name,
            $form->surname,
            $form->email,
            $form->password
        );
        $this->users->save($user);
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