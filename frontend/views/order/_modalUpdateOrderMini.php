<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

use kartik\datecontrol\DateControlAsset;
DateControlAsset::register($this);
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 14.12.2018
 * Time: 10:58
 */
/* @var $this yii\web\View */
/* @var $order \common\models\Order*/
?>
<?php $form = ActiveForm::begin([
    'id' => 'form-update-order',
    'action'  => 'order/update-ajax',
//    'options' => [
//        'onsubmit' => 'save(this)'
//    ]
]); ?>
<?php
Modal::begin([
    'header' => '<h4 id="modalTitle">Введите период аренды</h4>',
    'id' => 'modal',
    'size' => 'modal-md',
    'clientOptions' => ['backdrop' => 'static'],
    'footer' => '<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-success" >Сохранить</button>',
]);
?>
<div id='mainModalContent'>

    <div class="order-form">
        <div class="col-md-6">
            <?=
            $form->field($order, 'dateBegin')->widget(DateControl::class, [
                'type'=>DateControl::FORMAT_DATE,
                'ajaxConversion'=>false,
                'options'=>[
                    'id'=>'order_mini_datebegin',
                ],
                'widgetOptions' => [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                        'todayBtn' => true,
                    ]
                ]
            ])
            ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($order, 'dateEnd')->widget(DateControl::class, [
                'type'=>DateControl::FORMAT_DATE,
                'ajaxConversion'=>false,
                'options'=>[
                    'id'=>'order_mini_dateend',
                ],
                'widgetOptions' => [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                        'todayBtn' => true,
                    ]
                ]
            ])
            ?>
        </div>
    </div>

</div>
<?php
Modal::end();
?>
<?php ActiveForm::end(); ?>
<?php
$urlOrder_update_ajax=Url::toRoute("order/update-ajax");
$js = <<<JS
    
    $('#form-update-order').on('beforeSubmit', function(){
        var form = $(this);
        var data = form.serialize();
        // return;
        $.ajax({
                url: "$urlOrder_update_ajax",
                type: 'POST',
                data: data,
                success: function(response){
                    if (response.status==='success') {
                        // console.log(response.data);
                        form.trigger('reset');
                        $('#modal').modal('hide');
                        $('#orderHeaderBlock').html(response.data);
                        reloadPjaxs('#pjax_alerts','#cart-panel-pjax');
                    } else {
                        alert('Error!');    
                    }
                    
                },
                error: function(){
                    alert('Error!');
                }
        });
        return false;
    })
JS;
$this->registerJs($js);
?>