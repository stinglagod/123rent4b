<?php
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\Html;
?>
<div class="cart-main-area ptb--120 bg__white">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <form action="#">
                    <div class="table-content table-responsive">
                        <table>
                            <thead>
                            <tr>
                                <th class="product-thumbnail">Изображение</th>
                                <th class="product-name">Товар</th>
                                <th class="product-price">Цена</th>
                                <th class="product-quantity">Кол-во</th>
                                <th class="product-quantity">Период (аренда)</th>
                                <th class="product-subtotal">Итого</th>
                                <th class="product-remove">Удалить</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            //            TODO: getItems заменить на получить все товары в заказе
                            foreach ($order->getItems()->all() as $item){
                            /** @var $item \common\models\OrderProduct */

                            if (empty($item->product_id)) {
                                continue;
                            }
                            ?>
                            <tr>
                                <td class="product-thumbnail"><a href="<?=$item->product->getUrl()?>"><img src="<?=$item->product->getThumb(\common\models\File::THUMBMIDDLE)?>" alt="<?=$item->product->name?>" /></a></td>
                                <td class="product-name"><a href="<?=$item->product->getUrl()?>"><?=$item->product->name?></a></td>
                                <td class="product-price"><span class="amount"><?=$item->cost?> <?=$item->getCurrency()?></span></td>
                                <td class="product-quantity">
                                    <input type="number" value="<?=$item->qty?>" />
                                </td>
                                <td class="product-quantity"><input type="number" value="<?=$item->period?>" /></td>
                                <td class="product-subtotal"><?=$item->getSumm()?> руб.</td>
                                <td class="product-remove"><a href="#">X</a></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
//                    'id' => $grid_id,
                        'options' => [
                            'class'=>'table-content table-responsive'
                        ],
                        'pjax' => true,
                        'pjaxSettings'=>[
                            'options'=>[
                                'enablePushState' => false
                            ],
                        ],
                        'layout' => "{items}\n{summary}\n{pager}",
                        'columns' => [
                            [
                                'header' => 'Изображение',
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'product-thumbnail'],
                                'value' => function ($data) {
                                    return '<a href="'.$data->product->getUrl().'"><img src="'.$data->product->getThumb(\common\models\File::THUMBMIDDLE).'" alt="'.$data->product->name.'" /></a>';
                                }
                            ],
                            [
                                'class' => 'kartik\grid\EditableColumn',
                                'attribute' => 'name',
                                'header'=>'Товар',
                                'pageSummary' => 'Итого',
                                'headerOptions' => ['class' => 'product-name'],
//                                'width' => '30%', /*archi увеличил ширину*/
                                'vAlign' => 'middle',
                                'value' => function ($data) {
                                        return Html::a(Html::encode($data->product->name), $data->product->getUrl(),[
                                            'data-pjax'=>0,
                                            'class'=>'popover-product-name',
                                            'data-content'=> '<img src="'.$data->product->getThumb(\common\models\File::THUMBMIDDLE).'"/>',
                                        ]);
                                },
                                'format' => 'raw',
                                'editableOptions' => function ($data, $key, $index) {
                                    return [
                                        'name'=>'name',
                                        'value' => $data['name'],
                                        'header' => Yii::t('app', 'Наименование'),
                                        'size' => 'md',

                                        'formOptions' => [ 'action' => Url::toRoute(['order-product/update-ajax','id'=>$data['id']]) ],
                                    ];
                                },
                            ],
                            [
                                'class' => 'kartik\grid\EditableColumn',
                                'attribute' => 'cost',
                                'header'=>'Цена',
                                'format' => ['decimal', 2],
                                'pageSummary' => false,
                                'hAlign' => 'right',
                                'vAlign' => 'middle',
                                'headerOptions' => ['class' => 'kv-sticky-column'],
                                'contentOptions' => ['class' => 'kv-sticky-column'],
                                'editableOptions' => function ($data, $key, $index) use ($grid_id){
                                    return [
                                        'name'=>'cost',
                                        'value' => $data['cost'],
                                        'header' => Yii::t('app', 'Цена'),
                                        'size' => 'md',
                                        'formOptions' => [ 'action' => Url::toRoute(['order-product/update-ajax','id'=>$data['id']]) ],
                                        'pluginEvents' => [
                                            "editableSuccess"=>'gridOrderProduct.onEditableGridSuccess',
                                            "editableSubmit"=> 'gridOrderProduct.onEditableGridSubmit',
                                        ]
                                    ];
                                },
                                'refreshGrid'=>false,
                                'readonly' => function($data, $key, $index, $widget) use ($orderProduct) {
//              TODO: лишний запрос. Оптимизировать надо
                                    if (empty($orderProduct)) {
                                        $orderProduct=\common\models\OrderProduct::findOne($data['id']);
                                    }
                                    return ($orderProduct->readOnly()); // do not allow editing of inactive records
                                },
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
                                        'name'=>'qty',
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
                                'readonly' => function($data, $key, $index, $widget) use ($orderProduct) {
//              TODO: лишний запрос. Оптимизировать надо
                                    if (empty($orderProduct)) {
                                        $orderProduct=\common\models\OrderProduct::findOne($data['id']);
                                    }
                                    return ($orderProduct->readOnly()); // do not allow editing of inactive records
                                },
                                'refreshGrid'=>false,
                            ],
                            /*archi скрыл период из таблицы
                            [
                                'class' => 'kartik\grid\EditableColumn',
                                'attribute' => 'period',
                                'header'=>'Период',
                                'format' => ['decimal', 0],
                                'pageSummary' => false,
                                'hAlign' => 'right',
                                'vAlign' => 'middle',
                                'headerOptions' => ['class' => 'kv-sticky-column'],
                                'contentOptions' => ['class' => 'kv-sticky-column'],
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
                                'readonly' => function($data, $key, $index, $widget) use ($orderProduct) {
                    //              TODO: лишний запрос. Оптимизировать надо
                                    if (empty($orderProduct)) {
                                        $orderProduct=\common\models\OrderProduct::findOne($data['id']);
                                    }
                                    return ($orderProduct->readOnly()); // do not allow editing of inactive records
                                },
                                'refreshGrid'=>false,
                            ],*/
                            [
                                'attribute' => 'is_montage',
                                'header'=>'Монтаж',
                                'vAlign' => 'middle',
                                'hAlign' => 'center',
                                'value' => function ($data) {
                                    if ($data['is_montage'] == '1') {
                                        return Html::checkbox('is_montage',1,['disabled' => false,'class'=>'chk_is_montage','data-orderproduct_id'=>$data['id']]);
                                    } else {
                                        return Html::checkbox('is_montage',0,['disabled' => false,'class'=>'chk_is_montage','data-orderproduct_id'=>$data['id']]);

                                    }

                                }
                                , 'format' => 'raw'
                            ],
                            [
                                'class' => 'kartik\grid\EditableColumn',
                                'attribute' => 'comment',
                                'header'=>'Комментарий',
                                'headerOptions' => ['class' => 'text-center'],
                                'width' => '19%',
                                'vAlign' => 'middle',
                                'format' => 'raw',
                                'editableOptions' => function ($data, $key, $index) {
                                    return [
                                        'name'=>'comment',
                                        'value' => $data['comment'],
                                        'header' => Yii::t('app', 'Наименование'),
                                        'size' => 'md',

                                        'formOptions' => [ 'action' => Url::toRoute(['order-product/update-ajax','id'=>$data['id']]) ],
                                    ];
                                },
                            ],
                            [
                                'class' => 'kartik\grid\FormulaColumn',
                                'header' => 'Сумма',
                                'vAlign' => 'middle',
                                'value' => function ($model) {
                                    $summ=$model['cost']*$model['qty'];
                                    if ($model['type']==\common\models\OrderProduct::RENT) {
                                        $summ*=$model['period'];
                                    }
                                    return $summ;
                                },
                                'headerOptions' => ['class' => 'kartik-sheet-style'],
                                'hAlign' => 'right',
                                'format' => ['decimal', 2],
                                'mergeHeader' => true,
                                'pageSummary' => true,
                                'footer' => true
                            ],
                            [
                                'header'=>'Статус',
                                'value' => function ($model) {
                                    if ($status=\common\models\OrderProduct::findOne($model['id'])->status) {
                                        return $status->name;
                                    } else {
                                        return '';
                                    }
                                }
                            ],
                            [
                                'class' => 'kartik\grid\ActionColumn',
                                'template' => '{delete}',
                                'contentOptions' => ['class' => 'action-column'],
                                'buttons' => [
                                    'delete' => function ($url, $model, $key) {
                                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute(['order-product/delete-ajax','id'=>$model['id']]), [
                                            'title' => Yii::t('yii', 'Delete'),
                                            'data-pjax' => '#pjax_order-product_grid_'.$model['id'],
                                            'data-confirm'=>'Вы действительно хотите удалить позицию из заказа?',
                                            'data-method'=>'post'
                                        ]);
                                    },
                                ],

                            ],
                            [
                                'class' => 'kartik\grid\CheckboxColumn',
                                'headerOptions' => ['class' => 'kartik-sheet-style'],
                            ],
                        ],
                        'showPageSummary' => true,
                    ]); ?>
                    <div class="row">
                        <div class="col-md-8 col-sm-7 col-xs-12">

                        </div>
                        <div class="col-md-4 col-sm-5 col-xs-12">
                            <div class="cart_totals">
                                <h2>ИТОГО</h2>
                                <table>
                                    <tbody>
                                    <tr class="cart-subtotal">
                                        <th>Стоимоть товаров</th>
                                        <td><span class="amount"><?=$order->getSumm()?></span></td>
                                    </tr>
                                    <tr class="shipping">
                                        <th>Доставка</th>
                                        <td>
                                            <ul id="shipping_method">
                                                <li>
                                                    <input type="radio" />
                                                    <label>
                                                        Flat Rate: <span class="amount">£7.00</span>
                                                    </label>
                                                </li>
                                                <li>
                                                    <input type="radio" />
                                                    <label>
                                                        Free Shipping
                                                    </label>
                                                </li>
                                                <li></li>
                                            </ul>
                                            <p><a class="shipping-calculator-button" href="#">Calculate Shipping</a></p>
                                        </td>
                                    </tr>
                                    <tr class="order-total">
                                        <th>Итого</th>
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
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
