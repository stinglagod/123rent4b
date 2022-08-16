<?php

/* @var $this yii\web\View */
/* @var $model rent\forms\manage\Shop\PaymentForm */

$this->title = 'Добавить платеж';
$this->params['breadcrumbs'][] = ['label' => 'Касса', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

use kartik\datecontrol\DateControl;
use rent\helpers\PaymentHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm; ?>
<div class="payment-create">

    <div class="payment-form">

        <?php $form = ActiveForm::begin(); ?>

        <div class="box box-default">
                <div class="col-md-6">
                    <?=
                    $form->field($model, 'dateTime')->widget(DateControl::class, [
                        'type'=>DateControl::FORMAT_DATE,
                        'widgetOptions' => [
                            'pluginOptions' => [
                                'autoclose' => true
                            ]
                        ]
                    ])
                    ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'type_id')->dropDownList(PaymentHelper::paymentTypeList(), ['prompt' => Yii::t('app', 'Выберите')]) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'purpose_id')->dropDownList(PaymentHelper::paymentPurposeList(), ['prompt' => Yii::t('app', 'Выберите')]) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'sum')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model->payer, 'name')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model->payer, 'phone')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-md-12">
                    <?= $form->field($model, 'note')->textarea(['maxlength' => true]) ?>
                </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
