<?php

use rent\forms\manage\CRM\ContactForm;
use rent\helpers\ContactHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use kartik\touchspin\TouchSpin;
use yii\helpers\ArrayHelper;
use rent\helpers\PaymentHelper;

/* @var $this yii\web\View */
/* @var $model ContactForm */

?>

<?php $form = ActiveForm::begin([
        'id' => 'form-contact-create',
        'action' => ['create-ajax'],
        'options' => [ 'data-pjax_reload' => '#pjax_alerts'],
    ]); ?>
<?php
Modal::begin([
    'header' => '<h4>Добавление контакт</h4>',
    'id' => '_modalCreate',
    'size' => 'modal-md',
    'clientOptions' => ['backdrop' => 'static'],
    'footer' => '<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-success" class="">Сохранить</button>',
]);
?>
<div id='mainModalContent'>
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
<?php
Modal::end();
?>
<?php ActiveForm::end(); ?>
<?php
$js = <<<JS
    $('#form-contact-create').on('beforeSubmit', function () {
        let yiiform = $(this);
        let modal = $(this).find('.modal');
        
        $.ajax({
                type: yiiform.attr('method'),
                url: yiiform.attr('action'),
                data: yiiform.serializeArray(),
            }
        )
            .done(function(data) {
                if(data.success) {
                    // console.log('data is saved');
                    // reloadPjaxs("#pjax_alerts");
                    modal.modal('hide');
                    yiiform.trigger('reset');
                    document.location.reload();
                    // $.pjax.reload("#order-payment-grid");
                    // $.pjax.reload("#pjax_alerts");
                } else if (data.validation) {
                    // console.log('server validation failed');
                    yiiform.yiiActiveForm('updateMessages', data.validation, true); // renders validation messages at appropriate places
                } else {
                    console.log('incorrect server response');
                }
                // reloadPjaxs("#pjax_alerts",);
            })
            .fail(function () {
                // request failed
            })
    
        return false; // prevent default form submission
    })

JS;
$this->registerJs($js);
?>
