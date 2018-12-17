<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 14.12.2018
 * Time: 10:58
 */
/* @var $this yii\web\View */
/* @var $model \common\models\Movement*/
?>
<?php
Modal::begin([
    'header' => '<h4 id="modalTitle"><h4>'.$model->isNewRecord?'Создание нового передвижение':'Редактировние передвижения'.'</h4>',
    'id' => 'modal',
    'size' => 'modal-md',
    'clientOptions' => ['backdrop' => 'static'],
]);
?>
<div id='mainModalContent'>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
<?php
Modal::end();
?>
