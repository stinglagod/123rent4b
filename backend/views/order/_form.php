<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;
use kartik\tabs\TabsX;
use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\select2\Select2;
use common\models\Action;
use kartik\dialog\Dialog;
use yii\web\JsExpression;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
$selectOperations= Select2::widget([
    'name' => 'status',
    'hideSearch' => true,
    'data' => [
        Action::ISSUE => 'Выдать отмеченные',
        Action::RETURN => 'Получить отмеченные',
        Action::TOREPAIR => 'Отправить в ремонт',
        99 => 'Удалить отмеченные'
    ],
    'options' => ['placeholder' => 'Выберите операцию'],
    'pluginOptions' => [
        'allowClear' => true,
//        'width' => '30%'
    ],
    'pluginEvents' => [
        "select2:selecting" => "function(e) { changeOperation(e) }",
    ],
]);
?>
<div class="user-index box box-primary">
<!--    --><?php //Pjax::begin(['id' => 'pjax_order-product_grid']); ?>
    <?=$this->render('_gridOrderProduct',[
        'dataProvider'=>$dataProvider
    ])
    ?>
    <div class="pull-right">
        <?=$selectOperations?>
    </div>
<!--    --><?php //Pjax::end(); ?>
<?php
$items = [
    [
        'label'=>'<i class="glyphicon glyphicon-home"></i> Общее',
        'content'=>$this->render('_tabMain', [
            'model'=>$model,
//            'form'=>$form,
        ]),
        'active'=>true
    ],
    [
        'label'=>'<i class="glyphicon glyphicon-list-alt"></i> Оплата',
        'content'=>$this->render('_tabPayment', [
            'model'=>$model,
//            'form'=>$form,
        ]),
//            'linkOptions'=>[
////                                'data-url'=>Url::to(['/file/index','hash'=>new JsExpression("function (){return 'hi'}")])
//                'data-url'=>Url::to(['/file/index'])
//            ],
    ],
    [
        'label'=>'<i class="glyphicon glyphicon-list-alt"></i> Движения товаров',
        'content'=>$this->render('_tabWarehouse', [
            'model'=>$model,
            'dataProviderMovement'=>$dataProviderMovement,
//            'form'=>$form,
        ]),
//            'linkOptions'=>[
////                                'data-url'=>Url::to(['/file/index','hash'=>new JsExpression("function (){return 'hi'}")])
//                'data-url'=>Url::to(['/file/index'])
//            ],
    ],
    [
        'label'=>'<i class="glyphicon glyphicon-user"></i> Профиль клиента',
        'linkOptions'=>[
//                                'data-url'=>Url::to(['/file/index','hash'=>new JsExpression("function (){return 'hi'}")])
//            'data-url'=>Url::to(['/user/profile','id'=>$model->client_id])
        ],
    ],


];
?>
<!--<div class="row">-->
    <br><br>
    <?=TabsX::widget([
        'items'=>$items,
        'position'=>TabsX::POS_ABOVE,
        'encodeLabels'=>false
    ]);
    ?>
<!--</div>-->

</div>

<?php
    Modal::begin([
        'header' => '<h4 id="modalTitle"><h4></h4>',
        'id' => 'order-confirm-modal',
        'size' => 'modal-md',
        'clientOptions' => ['backdrop' => 'static'],
        'footer' => 'Кнопка',
    ]);
?>
<?php
    Pjax::begin(['id' => 'pjax_order-content-confirm-modal']);
    Pjax::end();
?>
<?php
    Modal::end();
?>
<?php
$urlContentConfirmModal=Url::toRoute("order/content-confirm-modal-ajax");
$content = Pjax::begin(['id' => 'pjax_order-content-confirm-modal']);
//$contentEnd = Pjax::end();
//$content = $content . $contentEnd;
$js = <<<JS
    function changeOperation(e) {
        var keys = $('#order-grid').yiiGridView('getSelectedRows').length;
        if (keys==0) {
            alert('Не выделено ни одного элемента');
            return false;
        }
        $.post({
           url: "$urlContentConfirmModal", // your controller action
           dataType: 'json',
           data: {
                    keylist: $('#order-grid').yiiGridView('getSelectedRows'),
                    operation: e.params.args.data.id
               },
           success: function(response) {
               // console.log(response);
               if (response.status === 'success') {
                    $("#modalBlock").html(response.data)
                    $('#modal').removeClass('fade');
                    $('#modal').modal('show'); 
               }
           },
        });
    }
JS;
$this->registerJs($js);
?>