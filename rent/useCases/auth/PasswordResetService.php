<?php
/**
 * Created by PhpStorm.
 * User: Aleksey
 * Date: 28.05.2020
 * Time: 13:04
 */

namespace rent\useCases\auth;


use rent\entities\User\User;
use rent\forms\auth\PasswordResetRequestForm;
use rent\forms\auth\ResetPasswordForm;
use rent\repositories\UserRepository;
use Yii;
use yii\mail\MailerInterface;

class PasswordResetService
{
    private $mailer;
    private $users;

    /**
     * Конструктор. При создании класса определяем UserRepository, MailerInterface
     * т.к. yii2 умный, то соответсвующие классы он сам находит и определяет
     * @param UserRepository $users
     * @param MailerInterface $mailer
     */
    public function __construct(UserRepository $users, MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        $this->users = $users;
    }

    /**
     * обрабатываем запрос на сменую пароля от формы PasswordResetRequestForm
     * с последующей отправкой почты
     * @param PasswordResetRequestForm $form
     * @throws \yii\base\Exception
     */
    public function request(PasswordResetRequestForm $form): void
    {
        /* @var $user User */
        $user = $this->users->getByEmail($form->email);

        if (!$user->isActive()) {
            throw new \DomainException('User is not active.');
        }

        $user->requestPasswordReset();
        $this->users->save($user);

        $sent = $this
            ->mailer
            ->compose(
                ['html' => 'auth/reset/confirm-html', 'text' => 'auth/reset/confirm-text'],
                ['user' => $user]
            )
            ->setTo($user->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();
        if (!$sent) {
            throw new \RuntimeException('Sending error.');
        }
    }

    /**
     * Проверяем Токен.
     * Не пустой и ищем естьи ли пользователь с таким токеном
     * @param $token
     */
    public function validateToken($token): void
    {
        if (empty($token) || !is_string($token)) {
            throw new \DomainException('Password reset token cannot be blank.');
        }
        if (!$this->users->existsByPasswordResetToken($token)) {
            throw new \DomainException('Wrong password reset token.');
        }
    }

    public function reset(string $token, ResetPasswordForm $form): void
    {
        $user = User::findByPasswordResetToken($token);

        if (!$user) {
            throw new \DomainException('User is not found.');
        }

        $user->resetPassword($form->password);

        if (!$user->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }
}