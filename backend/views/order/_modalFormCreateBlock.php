<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 14.12.2018
 * Time: 10:58
 */
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \common\models\OrderBlock*/
?>
<?php
Modal::begin([
    'header' => '<h4 id="modalTitle"><h4>'.$model->isNewRecord?'Создание нового блока':'Редактирование блока'.'</h4>',
    'id' => 'modal',
    'size' => 'modal-md',
    'clientOptions' => ['backdrop' => 'static'],
]);
?>
<div id='mainModalContent'>
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php
Modal::end();
?>
