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
/* @var $blocks \common\models\Block[] */

$items = [
    [
        'label'=>'<i class="glyphicon glyphicon-home"></i> Общее',
        'content'=>$this->render('_tabMain', [
            'model'=>$model,
            'blocks'=>$blocks,
            'users'=>$users,
            'statuses'=>$statuses
        ]),
        'active'=>true
    ],
    [
        'label'=>'<i class="glyphicon glyphicon-list-alt"></i> Оплата',
        'content'=>$this->render('_tabPayment', [
            'model'=>$model,
            'dataProviderCash' => $dataProviderCash
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

    <div class="user-index box box-primary">
    <?=TabsX::widget([
        'items'=>$items,
        'position'=>TabsX::POS_ABOVE,
        'encodeLabels'=>false
    ]);
    ?>
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

$js = <<<JS


JS;
$this->registerJs($js);
?>