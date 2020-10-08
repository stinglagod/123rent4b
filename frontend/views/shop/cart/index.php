<?php

/* @var $this yii\web\View */
/* @var $cart \rent\cart\Cart */

use rent\helpers\PriceHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

$this->title = 'Корзина';
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['/shop/catalog/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- cart-main-area start -->

<div class="cart-main-area ptb--40 bg__white">
    <div class="container">
        <div class="row">

        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-content table-responsive">
                    <table>
                        <thead>
                        <tr>
                            <th class="product-thumbnail">Изображение</th>
                            <th class="product-name">Продукт</th>
                            <th class="product-price">Цена</th>
                            <th class="product-quantity">Количество</th>
                            <th class="product-subtotal">Стоимость</th>
                            <th class="product-remove">Удалить</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($cart->getItems() as $item): ?>
                            <?php
                            $product = $item->getProduct();
                            $url = Url::to(['/shop/catalog/product', 'id' => $product->id]);
                            ?>
                            <tr>
                                <td class="product-thumbnail">
                                    <a href="<?= $url ?>">
                                        <?php if ($product->mainPhoto): ?>
                                            <img src="<?= $product->mainPhoto->getThumbFileUrl('file', 'cart_list') ?>" alt="" class="img-thumbnail" />
                                        <?php endif; ?>
                                    </a>
                                </td>
                                <td class="product-name">
                                    <a href="<?= $url ?>"><?= Html::encode($product->name) ?></a>
                                    <br>
                                    <small><?=Html::encode($item->getTypeName())?></small>
                                </td>
                                <td class="product-price"><span class="amount"><?= $item->getPrice_text() ?></span></td>
                                <td class="product-quantity">
                                    <?= Html::beginForm(['quantity', 'id' => $item->getId()]); ?>
                                    <div class="input-group btn-block" style="max-width: 200px;">
                                        <input type="text" name="quantity" value="<?= $item->getQuantity() ?>" size="1" class="form-control" />
                                        <span class="input-group-btn">
                                            <button type="submit" title="" class="btn btn-primary" style="height: 40px;" data-original-title="Обновить"><i class="glyphicon glyphicon-refresh"></i></button>
                                        </span>

                                    </div>
                                    <small>на складе: <?=$item->product->getQuantity()?> шт.</small>
                                    <?= Html::endForm() ?>
                                    <td class="product-subtotal"> <?= PriceHelper::format($item->getCost()) ?></td>
                                    <td class="product-remove"><a title="Удалить" href="<?= Url::to(['remove', 'id' => $item->getId()]) ?>" data-method="post">X</a></td>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php $form = ActiveForm::begin() ?>
                <div class="row">
                    <div class="col-md-8 col-sm-7 col-xs-12">
<!--                        <div class="col-md-12 col-sm-12 col-xs-12">-->
                            <div class="col-md-6">
                                <?=
                                $form->field($model, 'date_begin')->widget(DateControl::class, [
                                    'type'=>DateControl::FORMAT_DATE,
                                    'widgetOptions' => [
                                        'pluginOptions' => [
                                            'autoclose' => true
                                        ]
                                    ],
                                ])
                                ?>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-4">
                                <?=
                                $form->field($model, 'date_end')->widget(DateControl::class, [
                                    'type'=>DateControl::FORMAT_DATE,
                                    'widgetOptions' => [
                                        'pluginOptions' => [
                                            'autoclose' => true
                                        ]
                                    ],
                                ])
                                ?>
                            </div>

<!--                        </div>-->
                        <?= $form->field($model->customer, 'name')->textInput() ?>
                        <?= $form->field($model->customer, 'phone')->textInput() ?>
                        <?= $form->field($model, 'note')->textarea(['rows' => 3]) ?>
                    </div>
                    <div class="col-md-4 col-sm-5 col-xs-12">
                        <?php $cost = $cart->getCost() ?>
                        <div class="cart_totals">
                            <h2></h2>
                            <table>
                                <tbody>

                                <tr class="order-total">
                                    <th>Итого: </th>
                                    <td>
                                        <strong><span class="amount"><?= PriceHelper::format($cost->getTotal()) ?></span></strong>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="wc-proceed-to-checkout">
                                <a href="#" onclick="$(this).closest('form').submit();">Оформить</a>
                            </div>
                        </div>

                    </div>
                </div>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>

<!-- cart-main-area end -->

    
