<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \rent\helpers\ClientHelper;

/* @var $this yii\web\View */
/* @var $model \rent\forms\manage\Client\ClientEditForm */
/* @var $client \rent\entities\Client\Client */

$this->title = 'Update Client: ' . $client->name;
$this->params['breadcrumbs'][] = ['label' => 'Clients', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $client->name, 'url' => ['view', 'id' => $client->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="client-update">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxLength' => true]) ?>
    <?= $form->field($model, 'status')->dropDownList(ClientHelper::statusList()) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

