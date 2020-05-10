
<?php
/** @var $category \common\models\Category */
$this->title = $category->name;
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="categories-slider-area bg__white">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-lg-3 col-sm-4 col-xs-12 ">

                <?=
                $this->render('_leftMenuCatalog',
                    [
                        'menuCatalogItems' => $menuCatalogItems,
                    ]);
                ?>
                Фильтр
            </div>
            <div class="col-md-9 col-lg-9 col-sm-8 col-xs-12">
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <div class="producy__view__container">
                            <!-- Start Short Form -->
                            <div class="product__list__option">
                                <div class="order-single-btn">
                                    <select class="select-color selectpicker">
                                        <option>Sort by newness</option>
                                        <option>Match</option>
                                        <option>Updated</option>
                                        <option>Title</option>
                                        <option>Category</option>
                                        <option>Rating</option>
                                    </select>
                                </div>
                                <div class="shp__pro__show">
                                    <span>Showing 1 - 4 of 25 results</span>
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

                            <?= \yii\widgets\ListView::widget([
                                'dataProvider' => $productsDataProvider,
                                'itemView' => '_productGrid',
                                'summary' => "{sorter}<div class='shp__pro__show'><span>Показано c {begin} по {end} из {totalCount} (всего {pageCount} страниц)</span></div>",
                                'summaryOptions' => [
                                    'tag' => 'div',
                                    'class' => 'pull-left nam-page',
                                ],
                                'sorter' => [

                                    // ...
                                ],
//                                'options' => [
//                                    'tag' => 'div',
//                                    'id' => 'productList',
//                                ],
                                'itemOptions' => [
                                    'tag' => 'div',
        //                    'class' => 'product-layout product-block col-xs-12',
                                    'class' => 'col-md-4 col-lg-4 col-sm-4 col-xs-12',
                                ],
                            ]) ?>
                        </div>
                        <div role="tabpanel" id="list-view" class="single-grid-view tab-pane fade clearfix">
                            <?= \yii\widgets\ListView::widget([
                                'dataProvider' => $productsDataProvider,
                                'itemView' => '_productList',
                                'itemOptions' => [
                                    'tag' => 'div',
                                    'class' => 'single__list__content clearfix',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
