<?php
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'Доступ запрещен.';
?>
<div class="site-index">

    <div class="jumbotron">
        <h2>Вам запрещен доступ к этой странице!</h2>
        <?php if (!(Yii::$app->user->isGuest)) {
            echo Html::beginForm(['/site/logout'], 'post');
            echo Html::submitButton(
            'Войти под другой учетной записью',
            ['class' => 'btn btn-lg btn-success']
            );
            echo Html::endForm();
        } else {?>
            <p class="lead">Войдите в систему</p>
            <a class="btn btn-lg btn-success" href="login">Войти</a>
        <?php } ?>
    </div>
</div>