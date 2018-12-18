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
    'header' => '<h4 id="modalTitle"><h4>Приход/уход товара</h4>',
    'id' => 'modal',
    'size' => 'modal-lg',
    'clientOptions' => ['backdrop' => 'static'],
]);
?>
<div id='mainModalContent'>
<!--    --><?//= $this->render('_form', [
//        'model' => $model,
//    ]) ?>
</div>
<?php
Modal::end();
?>
