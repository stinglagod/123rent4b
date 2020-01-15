<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 16.01.2019
 * Time: 10:09
 */
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use kartik\touchspin\TouchSpin;
use \common\models\Action;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$operationName=array(
//    Action::SOFTRENT => 'Добавление в корзину',
//    Action::HARDRESERV => 'Получение оплаты',
    Action::ISSUE => [
        'title' => 'Выдача товара',
        'body'  => 'Следующие товары будут выданы клиенту:',
        'btnName'=>'Выдать',
        'btnClass'=>'btn btn-primary',
    ],
    Action::RETURN=> [
        'title'=>'Получение товара',
        'body'  => 'Следующие товары получены от клиента:',
        'btnName'=>'Получить',
        'btnClass'=>'btn btn-primary',

    ],
    Action::TOREPAIR=>[
        'title'=>'Передать в ремонт',
        'body'  => 'Следующие товары будут переданы в ремонт:',
        'btnName'=>'Передать',
        'btnClass'=>'btn btn-primary',
    ],
    0=>[
        'title'=>'Удаление товаров из заказа',
        'body'  => 'Следующие товары будут удалены из заказа:',
        'btnName'=>'Удалить',
        'btnClass'=>'btn btn-danger',
    ]
)
?>
<?php
    Modal::begin([
        'header' => '<h4 id="modalTitle"><h4>'.$operationName[$operation]['title'].'</h4>',
        'id' => 'modal',
        'size' => 'modal-lg',
        'clientOptions' => ['backdrop' => 'static'],

        'footer' => '<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Закрыть</button>
                    <button type="button" onclick="save()" class="'.$operationName[$operation]['btnClass'].'">'.$operationName[$operation]['btnName'].'</button>',
    ]);
?>
<?php
    $form = ActiveForm::begin([
        'id' => 'form-order-confirm-operation',
    ]);
?>
    <div id='mainModalContent'>
        <p><?=$operationName[$operation]['body']?></p>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'pjax' => true,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'header' => 'Код',
                    'value' => function (\common\models\OrderProduct $data) {
                        if ($data->type=='collect') {
                            return "";
                        } else {
                            return $data->product->name;
                        }

                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'product_id',
                    'value' => function (\common\models\OrderProduct $data) {
                        if ($data->type=='collect') {
                            return $data->name;
                        } else {
                            $name=$data->product->name;
                            if ($data->parent_id!=$data->id) {
                                $name.='<br><small>в рамках составной позиции: '.$data->parent->name.'</small>';
                            }
                            return $name;
                        }
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'qty',
                    'filter' => false,
                    'format' => 'raw',
                    'value' => function(\common\models\OrderProduct $data) use ($form,$operation){
                        return $form->field($data, "qty[$data->id]")->widget(TouchSpin::classname(), [
//                            'disabled' => true,
                            'pluginOptions' => [
                                'min' => 0,
                                'max' => $data->getOperationBalance($operation),
                                'step' => 1,
                                'initval' => $data->qty,
                                'maxboostedstep' => 10,
                                'buttonup_class' => 'btn btn-primary',
                                'buttondown_class' => 'btn btn-info',
                                'buttonup_txt' => '<i class="glyphicon glyphicon-plus-sign"></i>',
                                'buttondown_txt' => '<i class="glyphicon glyphicon-minus-sign"></i>'
                            ],
                            'value' => $data->qty,
                        ])->label(false);
//                        return $form->field($searchModel, "qty[$searchModel->id]")->textInput([
//                            'class' => 'form-control',
//                            'value' => $searchModel->qty,
//                        ])->label(false);
                    }
                ],
            ],
        ]); ?>

    </div>
<?php
    ActiveForm::end();
    Modal::end();
?>

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
