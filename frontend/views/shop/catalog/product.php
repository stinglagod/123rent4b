<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css" />
<?php

/* @var $this yii\web\View */
/* @var $product rent\entities\Shop\Product\Product */
/* @var $cartForm rent\forms\Shop\AddToCartForm */
/* @var $reviewForm rent\forms\Shop\ReviewForm */

use rent\helpers\PriceHelper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\StringHelper;
use kartik\widgets\TouchSpin;
use rent\entities\Shop\Order\Item\OrderItem;

$this->title = $product->name;

$this->registerMetaTag(['name' =>'description', 'content' => $product->meta->description]);
$this->registerMetaTag(['name' =>'keywords', 'content' => $product->meta->keywords]);

$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['index']];
$parentCategory=$product->getActualCategory();
foreach ($parentCategory->parents as $parent) {
    if (!$parent->isRoot()) {
        $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => ['category', 'id' => $parent->id]];
    }
}
$this->params['breadcrumbs'][] = ['label' => $parentCategory->name, 'url' => ['category', 'id' => $parentCategory->id]];
$this->params['breadcrumbs'][] = $product->name;

$this->params['active_category'] = $product->category;

//MagnificPopupAsset::register($this);
?>
<!-- Start Product Details -->
<section class="htc__product__details pb--100 bg__white">
    <div class="container">
        <div class="scroll-active">
            <div class="row">
                <div class="col-md-7 col-lg-7 col-sm-5 col-xs-12">
                    <div class="product__details__container product-details-5 owl-carousel">
                        <?php foreach ($product->photos as $i => $photo): ?>
                        <div class="scroll-single-product mb--30">
                            <a data-fancybox="gallery" data-src="<?=$photo->getThumbFileUrl('file', 'catalog_product') ?>">
                                <img src="<?=$photo->getThumbFileUrl('file', 'catalog_product') ?>" alt="<?= $i==0?Html::encode($product->name):'' ?>">
                            </a>
                        </div>
                        <?php endforeach;?>
                    </div>
                </div>
                <div class="sidebar-active col-md-5 col-lg-5 col-sm-7 col-xs-12 xmt-30">
                    <?php $form = ActiveForm::begin([
                        'action' => ['/shop/cart/add-ajax-post', 'id' => $product->id],
                        'options' => [
                            'class'=> 'form-product'
                        ],
                    ]) ?>
                    <div class="htc__product__details__inner ">
                        <div class="pro__detl__title pt--20">
                            <h1><?=Html::encode($product->name)?></h1>
                            <a class="btn-add-ajax" href="<?= Url::to(['/cabinet/wishlist/add-ajax', 'id' => $product->id]) ?>"><span class="ti-heart"></span></a>
                        </div>
                        <div class="pro__dtl__rating">
                            <ul class="pro__rating">
                                <li><span class="ti-star"></span></li>
                                <li><span class="ti-star"></span></li>
                                <li><span class="ti-star"></span></li>
                                <li><span class="ti-star"></span></li>
                                <li><span class="ti-star"></span></li>
                            </ul>
                            <span class="rat__qun">(Проголосовало: 0)</span>
                        </div>
                        <div class="pro__details">
                            <p><?= Html::encode(StringHelper::truncateWords(strip_tags($product->description), 20)) ?></p>
                            <a href="#" class="pro__details-link">Больше...</a>
                        </div>
                        <table class="pro__sizes" border="0">
                            <tr>
                                <td>Размер</td>
                                <td style="text-align: right;">1 412 см</td>
                            </tr>
                            <tr>
                                <td>Цвет:</td>
                                <td style="text-align: right;">Белый</td>
                            </tr>
                            <tr>
                                <td>Материал:</td>
                                <td style="text-align: right;">Металл</td>
                            </tr>
                        </table>
                        <?= $form->field($cartForm, 'type')
                            ->radioList($cartForm->typeList(),[
                                'item' => function ($index, $label, $name, $checked, $value) use ($product){
                                $check = $checked ? ' checked="checked"' : '';
                                return "<label class=\"form__param\"><input type=\"radio\" name=\"$name\" value=\"$value\"$check> <p class=\"title__5 form-check-input\"><span>$label:&nbsp;</span><span class=\"new__price\">".$product->getPriceByType_text($value)."</span></p></label><br>";
                            }])->label(false);
                        ?>

                        </div>

                        <div class="product-action-wrap">
                            <img class="product_basket" src="https://sun9-20.userapi.com/impg/Q0BzcT86k2fM_tx4vhkvY8EFGsTZYRTzm3MptA/cbQ_qC0I11A.jpg?size=37x44&quality=95&sign=778d95340487bde66d3363ce04c96e68&type=album" alt="" title="">
                            <div class="product-quantity">
                                <div class="prodict-statas"><label class="title__5">Количество:</label></div>
                                <?=
                                TouchSpin::widget([
                                    'model' => $cartForm,
                                    'attribute' => 'qty',
                                    'value'=>1,
//                                    'min' => 1,
//                                    'max' => 100,
                                    'options' => [
                                        'placeholder' => 'Количество ...',
                                        'pluginOptions' => ['min' => 1, 'max' => $product->getQuantity()]
                                    ],
                                    'pluginEvents' => [
                                        "change" => "function() { 
                                                qty=this.value;
                                                $.each($('.addToBasket'),function(index,value){
                                                    value.dataset.qty=qty;
                                                }); 
                                            }",
                                    ]
                                ]);
                                ?>
                                <span class="rat__qun">В наличии: <?=$product->getQuantity()?> шт.</span>
                            </div>
                            <a href="#" onclick="$(this).closest('form').submit();" class="add_to_basket">Добавить в корзину</a>
                        </div>
                        <ul class="pro__dtl__btn">
                            <li class="buy__now__btn"><a href="#" onclick="$(this).closest('form').submit();">Добавить в корзину</a></li>
                            <li><a class="btn-add-ajax" href="<?= Url::to(['/cabinet/wishlist/add-ajax', 'id' => $product->id]) ?>"><span class="ti-heart"></span></a></li>
                            <li><a href="#"><span class="ti-email"></span></a></li>
                        </ul>
                        <div class="delivery__block">
                            <img class="delivery__image" src="https://sun9-66.userapi.com/impg/x4_v0X88Unr2LEAGYARm7hFzSL2Eh80HsqMglQ/7DelrVv966c.jpg?size=37x45&quality=95&sign=a5376be06c1504ccfd2520a9892d01ee&type=album" alt="" title="">
                            <span class="delivery__title">Доставка</span>
                            <p class="delivery__text">Привоз/Вывоз по г.Ульяновск: от 3000 ₽. Вне города: +30 ₽/км.</p>
                        </div>
                        <div class="export__block">
                            <img class="export__image" src="https://sun9-22.userapi.com/impg/h2NBdd7xKP8rmMuoBMs-4-SNGPbXGpEULDrkHw/E8k5dn--Hz4.jpg?size=37x43&quality=95&sign=a4d7cf006b4696970a5d01fe6da3692f&type=album" alt="" title="">
                            <span class="export__title">Доставка:</span>
                            <p class="export__adress">Адрес</p>
                        </div>
                        <div class="pro__social__share">
                            <p>Поделиться: </p>
                            <ul class="pro__soaial__link">
                                <li><a href="#"><img src="https://sun9-32.userapi.com/impg/-goLaZTRrat8k_yRTDl-kRybkByd6OwIeL6jBQ/uXRRcPZ-M_o.jpg?size=26x25&quality=95&sign=652c5031940cad8c8be26404b0e66150&type=album" alt="" title=""></a></li>
                                <li><a href="#"><img src="https://sun9-39.userapi.com/impg/eTQ6Mq-k14NEJnk9UJRSivJbVyicfuP7uMQdcg/Ey_cN-HYJnw.jpg?size=26x25&quality=95&sign=bb91bef8a05d4f25c97e5d8a3953bd51&type=album" alt="" title=""></a></li>
                                <li><a href="#"><img src="https://sun9-68.userapi.com/impg/DiTgGltSLNoEBCsqyDFUpPGKAlV3yCr-WGBOkw/t8hN6ukybfU.jpg?size=26x25&quality=95&sign=33336348647509193bcc9df0679f2c40&type=album" alt="" title=""></a></li>
                                <li><a href="#"><img src="https://sun9-10.userapi.com/impg/BXDDD0tEmsqJtvRYZ7eqUo5BmBUmYwGJmydWrA/YFyAfxjzeaw.jpg?size=26x25&quality=95&sign=57a923dcd53a03bcb77ca62daa47c7d3&type=album" alt="" title=""></a></li>
                            </ul>
                        </div>
                    </div>
                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Product Details -->
<!-- Start Product tab -->
<section class="htc__product__details__tab bg__white pb--120">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                <ul class="product__deatils__tab mb--60" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#description" role="tab" data-toggle="tab">Описание</a>
                    </li>
                    <li role="presentation">
                        <a href="#sheet" role="tab" data-toggle="tab">Характеристики</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="product__details__tab__content">
                    <!-- Start Single Content -->
                    <div role="tabpanel" id="description" class="product__tab__content fade in active">
                        <div class="product__description__wrap">
                            <div class="product__desc">
                                <h2 class="title__6">Детали</h2>
                                <p>
                                    <?= Yii::$app->formatter->asHtml($product->description, [
                                        'Attr.AllowedRel' => array('nofollow'),
                                        'HTML.SafeObject' => true,
                                        'Output.FlashCompat' => true,
                                        'HTML.SafeIframe' => true,
                                        'URI.SafeIframeRegexp'=>'%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%',
                                    ]) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Content -->
                    <!-- Start Single Content -->
                    <div role="tabpanel" id="sheet" class="product__tab__content fade">
                        <div class="pro__feature">
                            <h2 class="title__6">Характеристики</h2>
                                <table class="table table-bordered">
                                    <tbody>
                                    <?php foreach ($product->values as $value): ?>
                                        <?php if (!empty($value->value)): ?>
                                            <tr>
                                                <th><?= Html::encode($value->characteristic->name) ?></th>
                                                <td><?= Html::encode($value->value) ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                        </div>
                    </div>
                    <!-- End Single Content -->
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Product tab -->

<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>

<?php
$js = <<<JS
    //Добавление товара в корзину
    $("body").on("submit", '.form-product', function(e) {
        console.log('Добавление в корзину');
        let form=$(this).closest('form');
        // console.log(form);
        // console.log(form.action);
        e.preventDefault();
        let formData = form.serialize();
        $.ajax({
            url: form[0].action,
            type: form[0].method,
            dataType: 'json',
            data: formData,
            success: function (data) {
                document.location.reload()
                reloadPjaxs('#pjax_alerts','#pjax-mini-cart')  
            }
        });
        return false;
    });

    // Customization example
      Fancybox.bind('[data-fancybox="gallery"]', {
        infinite: false
      });
JS;
$this->registerJs($js);

?>
