<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

?>
<div class="row">
    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <div class="producy__view__container">
            <!-- Start Short Form -->
            <div class="product__list__option">
                <div class="order-single-btn">
                    <select id="input-sort" class="select-color selectpicker" onchange="location = this.value;">
                        <?php
                        $values = [
                            '' => 'Сортировка по умолчанию',
                            'name' => 'Name (A - Z)',
                            '-name' => 'Name (Z - A)',
                            'price' => 'Price (Low &gt; High)',
                            '-price' => 'Price (High &gt; Low)',
                            '-rating' => 'Rating (Highest)',
                            'rating' => 'Rating (Lowest)',
                        ];
                        $current = Yii::$app->request->get('sort');
                        ?>
                        <?php foreach ($values as $value => $label): ?>
                            <option value="<?= Html::encode(Url::current(['sort' => $value ?: null])) ?>" <?php if ($current == $value): ?>selected="selected"<?php endif; ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="shp__pro__show">
                    <span>Показано <?= $dataProvider->getCount() ?> из <?= $dataProvider->getTotalCount() ?></span>
                </div>
            </div>
            <!-- End Short Form -->
            <!-- Start List And Grid View -->
            <ul class="view__mode" role="tablist">
                <li role="presentation" class="grid-view active"><a href="#grid-view" role="tab" data-toggle="tab"><i class="zmdi zmdi-grid"></i></a></li>
                <li role="presentation" class="list-view"><a href="#list-view" role="tab" data-toggle="tab"><i class="zmdi zmdi-view-list"></i></a></li>
            </ul>
            <!-- End List And Grid View -->
        </div>
    </div>
</div>
<div class="row">
    <div class="shop__grid__view__wrap another-product-style">
        <!-- Start Single View -->
        <div role="tabpanel" id="grid-view" class="single-grid-view tab-pane fade in active clearfix">
            <?php foreach ($dataProvider->getModels() as $product): ?>
                <?= $this->render('_productGrid', [
                    'product' => $product
                ]) ?>
            <?php endforeach; ?>
        </div>
        <!-- End Single View -->
        <!-- Start Single View -->
        <div role="tabpanel" id="list-view" class="single-grid-view tab-pane fade clearfix">
            <?php foreach ($dataProvider->getModels() as $product): ?>
                <?= $this->render('_productList', [
                    'product' => $product
                ]) ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6 text-left">
        <?= LinkPager::widget([
            'pagination' => $dataProvider->getPagination(),
        ]) ?>
    </div>
    <div class="col-sm-6 text-right">Показано <?= $dataProvider->getCount() ?> из <?= $dataProvider->getTotalCount() ?></div>
</div>
