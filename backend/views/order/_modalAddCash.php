<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 16.01.2019
 * Time: 10:09
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use kartik\touchspin\TouchSpin;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model \common\models\Cash */
/* @var $order_id integer */
/* @var $cashTypes \rent\entities\Shop\Order\PaymentType*/

?>

<?php
Modal::begin([
    'header' => '<h4 id="modalTitle"><h4>Добавление платежа</h4>',
    'id' => 'modal',
    'size' => 'modal-md',
    'clientOptions' => ['backdrop' => 'static'],
    'footer' => '<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-success" onclick="save()" data-order_id="'.$order_id.'" class="">Сохранить</button>',
]);
?>
<div id='mainModalContent'>
    <?php $form = ActiveForm::begin(['id' => 'form-order-add-cash',]); ?>



    <div class="col-md-6">
        <?=
        $form->field($model, 'dateTime')->widget(DateControl::class, [
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
        <?= $form->field($model, 'cashType_id')->dropDownList(ArrayHelper::map($cashTypes, 'id', 'name'), ['prompt' => Yii::t('app', 'Выберите')]) ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'payer')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'sum')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-12">
        <?= $form->field($model, 'note')->textarea(['maxlength' => true]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
Modal::end();
?>

<?php
$urlUpdateCash_ajax=Url::toRoute(["cash/update-ajax",'order_id'=>$order_id]);
$js = <<<JS
    function save() {
        var form = $('#form-order-add-cash');
        var data = form.serialize();
        // console.log(data);
        $.post({
            url: "$urlUpdateCash_ajax",
            dataType: 'json',
            data: data,
            success: function(response) {
               // console.log(response);
               if (response.status === 'success') {
                    $('#modal').modal('hide');
                    reloadPjaxs("#sum-order-pjax","#pjax_alerts","#order-cash-grid-pjax");
                    // $.pjax.reload({container: "#sum-order-pjax", async: false});
                    
               }
           },
        })
    }
JS;
$this->registerJs($js);
?>
