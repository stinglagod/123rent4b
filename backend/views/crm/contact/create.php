<?php

use rent\entities\CRM\Contact;
use rent\helpers\ContactHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \rent\helpers\ClientHelper;

/* @var $this yii\web\View */
/* @var $model Contact */

$this->title = 'Создать контакт';
$this->params['breadcrumbs'][] = ['label' =>'Контакты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-create">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box box-default">
        <div class="box-header with-border">Common</div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'name')->textInput(['maxLength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'surname')->textInput(['maxLength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'patronymic')->textInput(['maxLength' => true]) ?>
                </div>

            </div>
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'email')->textInput(['maxLength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'telephone')->textInput(['maxLength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'status')->dropDownList(ContactHelper::statusList()) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'note')->textarea(['maxLength' => true]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
