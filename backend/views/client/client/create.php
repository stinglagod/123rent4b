<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \rent\helpers\ClientHelper;

/* @var $this yii\web\View */
/* @var $model \rent\entities\Client\Client */

$this->title = 'Добавить клиента';
$this->params['breadcrumbs'][] = ['label' =>'Клиенты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-create">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxLength' => true]) ?>
    <?= $form->field($model, 'status')->dropDownList(ClientHelper::statusList()) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
