<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use rent\entities\Shop\Category;
use yii\helpers\ArrayHelper;
use kartik\detail\DetailView;
use common\models\File;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $product \rent\entities\Shop\Product\Product */
/* @var $form yii\widgets\ActiveForm */
/* @var $category Category*/
/* @var $model \rent\forms\manage\Shop\Product\ProductEditForm*/

?>
<?php Pjax::begin(['enablePushState' => false,'id' => 'pjax_product_form']); ?>
<div class="product-form " id="product-form">

    <?php
    $option=[
        'class' => 'img-responsive center-block ',
        'alt' => Html::encode($product->name),
    ];

    $btnClose='<button type="reset" class="kv-action-btn kv-btn-close" title="" data-toggle="tooltip" data-container="body" data-original-title="Закрыть"><span class="fa fa-close"></span></button>';
    $btnMotion='<a href="#" id="btnMotion" data-url="'.Url::toRoute(['movement/update-ajax','product_id'=>$product->id]).'" class="kv-action-btn" title="Приход" data-toggle="tooltip" data-container="body" data-original-title="Приход/Уход">Приход/Уход</a>';
    ?>

    <?php
    $detailViewAttributes=[
        [
            'group' => true,
            'label' => $this->render('_sliderBlock',['product'=>$product]),
//                'rowOptions' => ['class' => 'info'],
        ],
        [
            'columns' =>[
                [
                    'attribute'=>'name',
                    'valueColOptions'=>['style'=>'width:30%']
                ],
                [
                    'attribute'=>'code',
                    'valueColOptions'=>['style'=>'width:30%']
                ]
            ],
        ],
        [
            'columns' => [
                [
                    'attribute'=>'priceCost',
                    'type'=>DetailView::INPUT_HTML5,
                    'widgetOptions' =>[
                        'append' => ['content'=>'%']
                    ],
//                        'type'=>DetailView::INPUT
//                        'valueColOptions'=>['style'=>'width:30%'],
                ],
                [
                    'group' => true,
//                        'valueColOptions'=>['style'=>'width:30%']
                ],
            ]
        ],
        [
            'columns' =>[
                [
                    'attribute'=>'priceRent_new',
//                        'valueColOptions'=>['style'=>'width:30%']
                ],
                [
                    'group' => true,
//                        'valueColOptions'=>['style'=>'width:30%'],
                    'groupOptions'=>[
                        'class' =>'kv-edit-hidden'
                    ],
                    'label'=> function ($data)  {
                        return Html::beginTag('button', array(
                                'class' => 'btn btn-success addToBasket pull-right',
                                'data-id'=>$data->id,
                                'type'=>'button',
                                'data-toggle'=>'tooltip',
                                'title'=>'Сдача в аренду',
                                'width'=>'50px',
                            )).
                            Html::tag('i', '', array('class' => 'fa fa-cart-plus')).
                            Html::endTag('button');
                    }

                ],
            ],
        ],
        [
            'columns' => [
                [
                    'attribute'=>'priceSale_new',
//                        'valueColOptions'=>['style'=>'width:30%']
                ],
                [
                    'group' => true,
//                        'valueColOptions'=>['style'=>'width:30%'],
                    'displayOnly'=>false,
                    'groupOptions'=>[
                        'class' =>'align-right kv-edit-hidden'
                    ],
                    'label'=>
                        Html::beginTag('button', array(
                            'class' => 'btn btn-warning addToBasket pull-right',
                            'data-id'=>$product->id,
                            'data-pricesale'=>$product->priceSale,
                            'type'=>'button',
                            'data-toggle'=>'tooltip',
                            'title'=>'Продажа',
                            'width'=>'50px',
                        )).
                        Html::tag('i', '', array('class' => 'fa fa-cart-plus')).
                        Html::endTag('button')
                ],
            ]
        ],
        [
            'attribute'=>'description'
        ],
        [
            'attribute'=>'categories.main',
            'value' => ArrayHelper::getValue($product, 'category.name'),
        ],
        [
            'label' => 'Other categories',
            'value' => implode(', ', ArrayHelper::getColumn($product->categories, 'name')),
        ],
        [
            'label' => 'Tags',
            'value' => implode(', ', ArrayHelper::getColumn($product->tags, 'name')),
        ],
//        [
//            'attribute'=>'categoriesArray',
//            'format'=>'raw',
//            'value' => implode(', ', ArrayHelper::map($product->categories, 'id', 'name')),
//            'type'=>DetailView::INPUT_SELECT2,
//            'widgetOptions' => [
//                'data' => Category::find()->select(['name','id'])->indexBy('id')->column(),
//                'options' => ['placeholder' => 'Выберите категорию ...','multiple' => true],
//                'pluginOptions' => [
//                    'allowClear' => true
//                ],
//            ],
//        ],
//        [
//            'attribute'=>'on_site'
//        ],
//        [
//            'attribute'=>'tagsArray',
//            'format'=>'raw',
//            'value' => $product->tag,
//            'type'=>DetailView::INPUT_SELECT2,
//            'widgetOptions' => [
//                'data'=> \common\models\Tag::getAllTags(),
//                'options' => [
//                    'placeholder' => 'Установите теги ...',
//                    'multiple' => true
//                ],
//                'pluginOptions' => [
//                    'tags' => true,
//                    'tokenSeparators' => [','],
//                    'maximumInputLength' => 20,
//                    'allowClear' => true,
//                ],
//            ],
//        ],
    ];

//    foreach ($product->values as $value) {
//        $detailViewAttributes[]=[
//            'attribute'=>$value->value,
//            'label'=>$value->characteristic->name
//        ];
//    }
    $detailViewAttributes[]=[
        'columns' =>[
//            [
//                'group' => true,
//                'label' => 'Доступно для заказа: '.$product->getBalance().'<br>Всего в наличии на складе: '.$model->getBalanceStock(). " шт. ",
//                'rowOptions' => ['class' => 'info'],
//            ],
//                [
//                    'group' => true,
//                    'label' => '<a id="opencalendar" href="#" data-url="'.Url::toRoute(['product/modal-calendar']).'" data-id="'.$model->id.'">открыть календарь</a>',
//                    'valueColOptions'=>['style'=>'width:30%']
//                ],
            [
                'group' => true,
                'groupOptions'=>[
                    'class' =>'kv-view-hidden'
                ],
                'label' =>
                    Html::beginTag('button', array(
                        'class' => 'btn btn-block btn-success',
                        'data-id'=>$product->id,
                        'type'=>'button',
                        'data-toggle'=>'tooltip',
                        'type'=>'submit'
                    )).
//                        Html::tag('i', '', array('class' => 'fa fa-cart-plus')).
                    Html::tag('span',Yii::t('app', ' Сохранить')).
                    Html::endTag('button')
            ]

        ],
    ];
    ?>

    <?=
    DetailView::widget([
        'model'=>$model,
        'condensed'=>true,
        'hover'=>true,
        'mode'=>DetailView::MODE_VIEW,
        'id'=> 'detail-view',
        'formOptions' => [
            'id' => 'form-detail-view',
        ],
        'panelCssPrefix'=>'box box-',
        'panel'=>[
            'heading'=>$product->name,
            'type'=>DetailView::TYPE_PRIMARY,

        ],
        'buttons1'=>$btnMotion. ' {update} {delete} {reset} '.$btnClose,
        'buttons2'=>$btnMotion. ' {view}  {save} {delete} {reset} '.$btnClose,
        'deleteOptions'=>[
            'url'=>['delete', 'id' => $product->id],
            'data'=>[
//                'confirm'=>Yii::t('app', 'D?'),
                'method'=>'post',
            ],
        ],
        'attributes'=>$detailViewAttributes,
    ]);?>
    <div class="col-md-1"></div>
    <div class="col-md-10">

    </div>

</div>


<?php

$js = <<<JS
    $('#imageGallery').lightSlider({
        gallery:true,
        item:1,
        loop:true,
        thumbItem:9,
        slideMargin:0,
        enableDrag: false,
        currentPagerPosition:'left',
        onSliderLoad: function(el) {
            el.lightGallery({
                selector: '#imageGallery .lslide'
            });
        }   
    });
JS;
$this->registerJs($js);
?>
<?php Pjax::end(); ?>
