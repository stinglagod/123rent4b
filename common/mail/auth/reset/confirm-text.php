<?php

/* @var $this yii\web\View */
/* @var $user \rent\entities\User\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/reset/confirm', 'token' => $user->password_reset_token]);
?>
    Добрый день <?= $user->shortName ?>,

    Для сброса пароля перейдите по ссылке:

<?= $resetLink ?>