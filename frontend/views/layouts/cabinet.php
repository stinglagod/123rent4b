<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;

?>
<?php $this->beginContent('@frontend/views/layouts/main.php') ?>
<?=$this->render('_breadcrumb');?>
<div class="wishlist-area ptb--20 bg__white">
    <div class="container">
        <div class="row">
            <div id="content" class="col-sm-9">
                <?= $content ?>
            </div>
            <aside id="column-right" class="col-sm-3 hidden-xs">
                <div class="list-group">

                    <a href="<?= Html::encode(Url::to(['/cabinet/default/index'])) ?>" class="list-group-item">Мой Профиль</a>
                    <a href="<?= Html::encode(Url::to(['/cabinet/wishlist/index'])) ?>" class="list-group-item">Избранное</a>
                    <a href="<?= Html::encode(Url::to(['/auth/auth/logout'])) ?>" class="list-group-item">Выйти</a>
                </div>
            </aside>
        </div>
    </div>
</div>

<?php $this->endContent() ?>
