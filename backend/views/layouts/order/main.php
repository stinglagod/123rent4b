<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;

/* @var $this \yii\web\View */
/* @var $content string */


if ((Yii::$app->controller->action->id === 'login')or(Yii::$app->user->isGuest)) {
    /**
     * Do not use this code in your template. Remove it.
     * Instead, use the code  $this->layout = '//main-login'; in your controller.
     */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAss::register($this);
    }

    dmstr\web\AdminLteAsset::register($this);
    $appAsset=\backend\assets\AppAsset::register($this);

    $directoryAsset =$appAsset->baseUrl;
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?= $this->render(
            'header.php',
            [
                'directoryAsset' => $directoryAsset,
//                'appAsset' => $appAsset
            ]
        ) ?>

        <?= $this->render(
            'content.php',
            [
                'content' => $content,
                'directoryAsset' => $directoryAsset,
//                'appAsset' => $appAsset
            ]
        ) ?>

    </div>

    <?php $this->endBody() ?>


    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
