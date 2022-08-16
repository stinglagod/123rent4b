<?php

/* @var $this yii\web\View */
/* @var $model rent\forms\manage\Shop\PaymentForm */

$this->title = 'Поступление Д/С';
$this->params['breadcrumbs'][] = ['label' => 'Касса', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

use kartik\datecontrol\DateControl;
use rent\helpers\PaymentHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm; ?>
<?php $form = ActiveForm::begin(); ?>
<div class="payment-create">
    <div class="row">
        <div class="col-md-6">
            <p>
                <?= Html::a('Вернуться назад', ['index'], ['class' => 'btn btn-default']) ?>
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

            </p>
        </div>
        <div class="col-md-6">
        </div>
    </div>
    <div class="payment-form">



        <div class="box box-default">
            <div class="box-header with-border">Common</div>
            <div class="box-body">
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

                <div class="col-md-12">
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
        </div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
