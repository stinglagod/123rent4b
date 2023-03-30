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

//    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
//    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@app/web/dist');
    $directoryAsset =$appAsset->baseUrl;
//    print_r($directoryAsset);
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
            'left.php',
            [
                'directoryAsset' => $directoryAsset,
//                'appAsset' => $appAsset
            ]
        )
        ?>

        <?= $this->render(
            'content.php',
            [
                'content' => $content,
                'directoryAsset' => $directoryAsset,
//                'appAsset' => $appAsset
            ]
        ) ?>

    </div>
<!--Общее модальное окно-->
    <div id="modalBlock">

    </div>
    <?php $this->endBody() ?>

    <?if (YII_ENV_DEV) :?>

    <div>
        <label>Пользователь:</label> <?=(Yii::$app->settings->user)?Yii::$app->settings->user->name:'не определен'?> <br>
        <label>Пользователь(логин):</label> <?=(Yii::$app->settings->user)?Yii::$app->settings->user->username:'не определен'?> <br>
        <label>Сайт:</label> <?=(Yii::$app->settings->site)?Yii::$app->settings->site->name:'не определен'?> <br>
        <label>Клиент:</label> <?=(Yii::$app->settings->client)?Yii::$app->settings->client->name:'не определен'?><br>
<!--        --><?//dump(Yii::$app->settings)?>
    </div>
    <?endif;?>

    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
