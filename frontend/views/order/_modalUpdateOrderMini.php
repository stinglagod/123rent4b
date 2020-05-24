<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 14.12.2018
 * Time: 10:58
 */
/* @var $this yii\web\View */
/* @var $order \common\models\Order*/
?>

<?php
Modal::begin([
    'header' => '<h4 id="modalTitle"><h4>'.$order->isNewRecord?'Создание нового заказа':'Редактировние заказа'.'</h4>',
    'id' => 'modal',
    'size' => 'modal-md',
    'clientOptions' => ['backdrop' => 'static'],
    'footer' => '<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-success">Сохранить</button>',
]);
?>
<div id='mainModalContent'>
    <?php $form = ActiveForm::begin([
        'id' => 'form-update-order',
        'action'  => 'order/update-ajax',
//    'options' => [
//        'onsubmit' => 'save(this)'
//    ]
    ]); ?>
    <div class="order-form">
        <div class="col-md-6">
            <?=
            $form->field($order, 'dateBegin')->widget(DateControl::class, [
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
            $form->field($order, 'dateEnd')->widget(DateControl::class, [
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
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
Modal::end();
?>

<?php
$urlOrder_update_ajax=Url::toRoute("order/update-ajax");
$js = <<<JS
    
    $('#form-update-order').on('beforeSubmit', function(){
        // alert('prinve');  
        console.log(this);
        var form = $(this);
        console.log(form);
        var data = form.serialize();
        console.log(data);
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
                        reloadPjaxs('#pjax_alerts','#order-index-grid-pjax');
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
    // $("body").on('beforeSubmit','#modalBlock', function(){
    //     console.log($(this));
    // })
    //      //отправляем данные в модалььном окне
    //  $('#modalBlock').on('beforeSubmit', function(){
    //      console.log('tut2');
    //     var form =$(this).find('form');
    //     var data = form.serialize();
    //     $.ajax({
    //             url: form.attr('action'),
    //             type: 'POST',
    //             data: data,
    //             success: function(response){
    //                 // console.log(response.data);
    //                 console.log('tut3');
    //                 form.trigger('reset');
    //                 $('#modal').modal('hide');
    //                 $('#orderHeaderBlock').html(response.data);
    //                 // $.pjax.reload({container: "#pjax_alerts", async: false});
    //             },
    //             error: function(){
    //                 alert('Error!');
    //             }
    //     });
    //     return false;
    // });
JS;
$this->registerJs($js);
?>