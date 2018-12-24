<?php
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use yii\helpers\Html;
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 24.12.2018
 * Time: 23:32
 */?>
<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <!--    --><?//= $form->field($model, 'cod')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <div class="col-md-6">
        <?=
        $form->field($model, 'dateBegin')->widget(DateControl::class, [
            'type'=>DateControl::FORMAT_DATE,
            'ajaxConversion'=>false,
            'widgetOptions' => [
                'pluginOptions' => [
                    'autoclose' => true
                ]
            ]
        ])
        ?>
    </div>
    <div class="col-md-6">
        <?=
        $form->field($model, 'dateEnd')->widget(DateControl::class, [
            'type'=>DateControl::FORMAT_DATE,
            'ajaxConversion'=>false,
            'widgetOptions' => [
                'pluginOptions' => [
                    'autoclose' => true
                ]
            ]
        ])
        ?>
    </div>
    <?= $form->field($model, 'customer')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <!--    --><?//= $form->field($model, 'created_at')->textInput() ?>
    <!---->
    <!--    --><?//= $form->field($model, 'updated_at')->textInput() ?>
    <!---->
    <!--    --><?//= $form->field($model, 'autor_id')->textInput() ?>
    <!---->
    <!--    --><?//= $form->field($model, 'lastChangeUser_id')->textInput() ?>
    <!---->
    <!--    --><?//= $form->field($model, 'is_active')->dropDownList([ 'active' => 'Active', 'inactive' => 'Inactive', 'deleted' => 'Deleted', ], ['prompt' => '']) ?>
    <!---->
    <!--    --><?//= $form->field($model, 'client_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>