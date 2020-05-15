<?php
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\editable\Editable;
use yii\widgets\Pjax;

$this->title = "Корзина";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cart-main-area ptb--40 bg__white">
    <div class="container">

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'id' => 'cart_grid',
                        'options' => [
//                            'class'=>'table-content table-responsive'
                        ],
                        'containerOptions' => [
                            'class'=>'table-content table-responsive'
                        ],
                        'pjax' => true,
                        'layout' => "{items}",
                        'pjaxSettings'=>[
                            'options'=>[
                                'enablePushState' => false
                            ],
                        ],
                        'columns' => [
                            [
                                'header' => 'Изображение',
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'product-thumbnail'],
                                'contentOptions' => ['class' => 'product-thumbnail'],
                                'value' => function ($data) {
                                    /** @var \common\models\OrderProduct $data */
                                    if ($data->product) {
                                        return '<a href="'.$data->product->getUrl().'"><img src="'.$data->product->getThumb(\common\models\File::THUMBMIDDLE).'" alt="'.$data->product->name.'" /></a>';
                                    } else {
                                        return "no foto";
                                    }

                                }
                            ],
                            [
                                'attribute' => 'name',
                                'header'=>'Товар',
                                'headerOptions' => ['class' => 'product-name'],
                                'contentOptions' => ['class' => 'product-name'],
                                'hAlign' => 'right',
                                'vAlign' => 'middle',
                                'value' => function ($data) {
                                    /** @var \common\models\OrderProduct $data */
                                    if ($data->product) {
                                        return Html::a(Html::encode($data->product->name), $data->product->getUrl(), [
                                            'data-pjax' => 0,
                                            'class' => 'popover-product-name',
                                            'data-content' => '<img src="' . $data->product->getThumb(\common\models\File::THUMBMIDDLE) . '"/>',
                                        ]);
                                    } else {
                                        return $data->service->name;
                                    }
                                },
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'cost',
                                'header'=>'Цена',
//                                'format' => ['decimal', 2],
                                'format' => 'raw',
                                'hAlign' => 'right',
                                'vAlign' => 'middle',
                                'headerOptions' => ['class' => 'product-price'],
                                'contentOptions' => ['class' => 'product-price'],
                                'value' => function($data) {
                                    return '<span class="amount">'.$data->cost.' '.$data->getCurrency().'</span>';
                                }
                            ],
                            [
                                'class' => 'kartik\grid\EditableColumn',
                                'attribute' => 'qty',
                                'header'=>'Кол-во',
                                'format' => ['decimal', 0],
                                'pageSummary' => true,
                                'hAlign' => 'right',
                                'vAlign' => 'middle',
                                'headerOptions' => ['class' => 'kv-sticky-column'],
                                'contentOptions' => ['class' => 'kv-sticky-column'],
                                'editableOptions' => function ($data, $key, $index) use ($grid_id){
                                    return [
                                        'header' => Yii::t('app', 'Количество'),
//                                        'name'=>'qty',
                                        'value' => $data['qty'],
                                        'size' => 'md',
                                        'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                                        'options' => [
                                            'pluginOptions' => ['min' => 0, 'max' => 5000]
                                        ],
                                        'formOptions' => [ 'action' => Url::toRoute(['order-product/update-ajax','id'=>$data['id']]) ],
                                        'pluginEvents' => [
                                            "editableSuccess"=>'gridOrderProduct.onEditableGridSuccess',
                                            "editableSubmit"=> 'gridOrderProduct.onEditableGridSubmit',
                                        ]
                                    ];
                                },
                                'refreshGrid'=>false,
                            ],
                            [
                                'class' => 'kartik\grid\EditableColumn',
                                'attribute' => 'period',
                                'header'=>'Период(аренда)',
                                'format' => ['decimal', 0],
                                'pageSummary' => false,
                                'hAlign' => 'right',
                                'vAlign' => 'middle',
                                'headerOptions' => ['class' => 'kv-sticky-column product-subtotal'],
                                'contentOptions' => ['class' => 'kv-sticky-column product-subtotal'],
                                'editableOptions' => function ($model, $key, $index) use ($grid_id){
                                    return [
                                        'header' => Yii::t('app', 'Период'),
                                        'size' => 'md',
                                        'name'=>'period',
                                        'value' => $model['period'],
                                        'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                                        'options' => [
                                            'pluginOptions' => ['min' => 0, 'max' => 5000]
                                        ],
                                        'formOptions' => ['action' => Url::toRoute(['order-product/update-ajax', 'id' => $model['id']])],
                                        'pluginEvents' => [
                                            "editableSuccess"=>'gridOrderProduct.onEditableGridSuccess',
                                            "editableSubmit"=> 'gridOrderProduct.onEditableGridSubmit',
                                        ]
                                    ];
                                 },
                                'refreshGrid'=>false,
                            ],
                            [
                                'class' => 'kartik\grid\FormulaColumn',
                                'header' => 'Итого',
                                'hAlign' => 'right',
                                'vAlign' => 'middle',
                                'value' => function ($model) {
                                    $summ=$model['cost']*$model['qty'];
                                    if ($model['type']==\common\models\OrderProduct::RENT) {
                                        $summ*=$model['period'];
                                    }
                                    return $summ;
                                },
                                'headerOptions' => ['class' => 'kartik-sheet-style product-subtotal'],
                                'contentOptions' => ['class' => 'product-subtotal'],
                                'format' => ['decimal', 2],
                                'mergeHeader' => true,
                                'footer' => true
                            ],
                            [
                                'class' => 'kartik\grid\ActionColumn',
                                'header' => 'Удалить',
                                'template' => '{delete}',
                                'headerOptions' => ['class' => 'product-remove'],
                                'contentOptions' => ['class' => 'action-column product-remove'],
                                'buttons' => [
                                    'delete' => function ($url, $model, $key) {
                                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute(['order-product/delete-ajax','id'=>$model['id']]), [
                                            'title' => Yii::t('yii', 'Delete'),
                                            'data-pjax' => 1,
                                            'data-confirm'=>'Вы действительно хотите удалить позицию из заказа?',
                                            'data-method'=>'post'
                                        ]);
                                    },
                                ],

                            ],
                        ],
                    ]); ?>
                    <div class="row">
                        <div class="col-md-8 col-sm-7 col-xs-12">

                        </div>
                        <?php Pjax::begin(['id'=>'sum-order-pjax']); ?>
                            <div class="col-md-4 col-sm-5 col-xs-12">
                                <div class="cart_totals">
                                    <h2>ИТОГО</h2>
                                    <table>
                                        <tbody>
                                        <tr class="cart-subtotal">
                                            <th>Заказ:</th>
                                            <td><span class="amount"><?=$order->getSumm()?></span></td>
                                        </tr>
                                        <tr class="shipping">
                                            <th>Доставка:</th>
                                            <td>
                                                <ul id="shipping_method">
                                                    <li>
                                                        <input type="radio" name="delivery" />
                                                        <label>
                                                            Курьер по городу: <span class="amount">500 руб.</span>
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <input type="radio" name="delivery"/>
                                                        <label>
                                                            Самовывоз: <span class="amount">0 руб.</span>
                                                        </label>
                                                    </li>
                                                    <li></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><hr></td>
                                            <td><hr></td>
                                        </tr>
                                        <tr class="order-total">
                                            <th>Итого:</th>
                                            <td>
                                                <strong><span class="amount"><?=$order->getSumm()?></span></strong>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <div class="wc-proceed-to-checkout">
                                        <a href="<?=Url::toRoute(["order/checkout"])?>">Перейти к оформлению</a>
                                    </div>
                                </div>
                            </div>
                        <?php Pjax::end(); ?>
                    </div>


            </div>
        </div>
    </div>
</div>

<?php
$js = <<<JS
// Найден небольшой глюк с Editable. событие editableSuccess возникает после перезагрузки gridа pjax.
// Поэтому при обновлении событие срабатывается и все pjax обновляются.
// Сделал проверку на первый запуск
    var first=0;
    var gridOrderProduct = {
        onEditableGridSuccess: function (event, val, form, data) {
            if (first) {
                first=0;
                // reloadPjaxs('#pjax_alerts','#cart_grid-pjax');
                reloadPjaxs('#cart_grid-pjax');
            }
        },
        onEditableGridSubmit: function (val, form) {
            first=1;
        }
    }
JS;
$this->registerJs($js,yii\web\View::POS_BEGIN);
// при удалении позиции нужно нобновить общие итоги. не нашелся лучше сделать чем отлавливать pjax грида
$js = <<<JS
    jQuery(document).on("pjax:success", '#cart_grid-pjax',  function(event){
        reloadPjaxs('#sum-order-pjax','#pjax_alerts');
        // $.pjax.reload({container:"#sum-order-pjax",timeout:2e3})
    });
JS;
$this->registerJs($js,yii\web\View::POS_END);


?>