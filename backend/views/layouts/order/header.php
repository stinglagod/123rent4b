<?php
use yii\helpers\Html;

use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use rent\forms\manage\Shop\Order\OrderCartForm;


/* @var $this \yii\web\View */
/* @var $content string */

/** @var \rent\forms\manage\Shop\Order\OrderCartForm $orderCartForm */
$orderCartForm=new OrderCartForm();
?>


<?php
$form = ActiveForm::begin([
    'id' => 'header-clients_form'
]);
?>
<header class="main-header">
    <nav class="navbar navbar-static-top" role="navigation" style="margin-left: 0px;">
        <div class="navbar-client">
            <?=$form->field($orderCartForm, 'order_id')
                ->label(false)
                ->dropDownList($orderCartForm->ordersList(), [
                    'id' => 'dep-drop_order_id',
                ]);?>
        </div>
        <div class="navbar-client">
            <?=$form->field($orderCartForm, 'block_id')
                ->label(false)
                ->widget(DepDrop::class, [
                    'options'=>[
                        'id'=>'dep-drop_block_id'
                    ],
                    'data'=>$orderCartForm->blocksList(),
                    'pluginOptions'=>[
                        'depends'=>['dep-drop_order_id'],
                        'placeholder'=>'Выберите...',
                        'url'=>Url::to(['shop/order/list-blocks']),
                        'dataType'=> "json",
                        'loadingText' => 'Загрузка ...',
                    ]
            ]);?>
        </div>
        <?=$orderCartForm->collect_id?>
        <div class="navbar-client">
            <?=$form->field($orderCartForm, 'collect_id')
                ->label(false)
                ->widget(DepDrop::class, [
                    'options'=>[
                        'id'=>'dep-drop_collect_id'
                    ],
                    'data'=>$orderCartForm->collectList(),
                    'pluginOptions'=>[
                        'depends'=>['dep-drop_block_id'],
                        'placeholder'=>'Выберите...',
                        'url'=>Url::to(['shop/order/list-collects']),
                        'dataType'=> "json",
                        'loadingText' => 'Загрузка ...',
                        'prompt' =>'Промпт'
                    ]
                ]);?>
        </div>
    </nav>
</header>

<?php ActiveForm::end();?>

<?php


$js = <<<JS

JS;
$this->registerJs($js);
?>
