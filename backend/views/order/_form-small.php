<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

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

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$urlOrder_product_movement_ajax=Url::toRoute("order-product/movement-ajax");
$js = <<<JS
    function save() {
        var form = $('#form-order-confirm-operation');
        var data = form.serialize()+'&operation='+"$operation";
        // console.log(data);
        // alert('Сохраняем');return false;
        $.post({
            url: "$urlOrder_product_movement_ajax",
            dataType: 'json',
            data: data,
            success: function(response) {
               // console.log(response);
               if (response.status === 'success') {
                    $('#modal').modal('hide');
                    $.pjax.reload({container: "#pjax_alerts", async: false});
                    $.pjax.reload({container: "#order-movement-grid-pjax", async: false});
                    
               }
           },
        })
    }
JS;
$this->registerJs($js);
?>