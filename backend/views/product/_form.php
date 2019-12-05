<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\models\Category;
use yii\helpers\ArrayHelper;
use kartik\detail\DetailView;
use common\models\File;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */
/* @var $category Category*/
/* @var $orderblock_id integer */
?>
<?php Pjax::begin(['enablePushState' => false,'id' => 'pjax_product_form']); ?>
<div class="product-form " id="product-form">

    <?php
    $option=[
        'class' => 'img-responsive center-block ',
        'alt' => Html::encode($model->name),
    ];
    $sliderBlock='<div class="row" style="width: 800px; padding: 30px;"><div class="col-md-12 center-block" >';
    if ($images=$model->getFiles(File::IMAGE)) {
        $sliderBlock.='<ul id="imageGallery">';
        foreach ($images as $image) {
            /** @var File $image*/
            $sliderBlock.="
            <li data-thumb='".$image->getUrl(File::THUMBSMALL)."' data-src='".$image->getUrl()."'>
                <img src='".$image->getUrl(File::THUMBMIDDLE)."' class='center-block'/>
            </li>
            ";
        }
        $sliderBlock.='</div></div>';
    } else {
        $sliderBlock.= Html::a(Html::img($model->getThumb(), $option), "#" , array('class' => 'lazy lazy-loaded viewProduct'));
    }
    $sliderBlock.='</div>';
//    if ($model->isNewRecord) {
//        $sliderBlock.='<center>Для добавление изображений необходимо сохранить товар</center>';
//    } else {
        $sliderBlock.='<button class="btn btn-default uplImgProduct center-block" data-hash="'.$model->hash.'"  type="button"><i class="glyphicon glyphicon-download-alt" aria-hidden="true"></i>Загрузить изображения</button></div>';
//    }


    $btnClose='<button type="reset" class="kv-action-btn kv-btn-close" title="" data-toggle="tooltip" data-container="body" data-original-title="Закрыть"><span class="fa fa-close"></span></button>';
//    $btnMotion='<button id="btnMotion" data-url="'.Url::toRoute(['movement/update-ajax','product_id'=>$model->id]).'" class="kv-action-btn" title="Приход" data-toggle="tooltip" data-container="body" data-original-title="Приход/Уход">Приход/Уход</button>';
    $btnMotion='<a href="#" id="btnMotion" data-url="'.Url::toRoute(['movement/update-ajax','product_id'=>$model->id]).'" class="kv-action-btn" title="Приход" data-toggle="tooltip" data-container="body" data-original-title="Приход/Уход">Приход/Уход</a>';
    ?>


<!--    --><?php //$calendar = \yii2fullcalendar\yii2fullcalendar::widget(array(
////        'events'=> $events,
//        'events' => Url::to(['product/calendar-ajax','product_id'=>$model->id])
//    )) ?>

    <?php
        $detailViewAttributes=[
            [
                'group' => true,
                'label' => $sliderBlock,
//                'rowOptions' => ['class' => 'info'],
            ],
            [
                'columns' =>[
                    [
                        'attribute'=>'name',
                        'valueColOptions'=>['style'=>'width:30%']
                    ],
                    [
                        'attribute'=>'cod',
                        'valueColOptions'=>['style'=>'width:30%']
                    ]
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute'=>'pricePrime',
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
                        'attribute'=>'priceRent',
//                        'valueColOptions'=>['style'=>'width:30%']
                    ],
                    [
                        'group' => true,
//                        'valueColOptions'=>['style'=>'width:30%'],
                        'groupOptions'=>[
                            'class' =>'kv-edit-hidden'
                        ],
                        'label'=> function ($data) use ($orderblock_id) {
                            if ($orderblock_id) {
                                return Html::beginTag('button', array(
                                    'class' => 'btn btn-success addToBasket pull-right',
                                    'data-id'=>$data->id,
                                    'data-pricerent'=>$data->priceRent,
                                    'data-orderblock_id'=>$orderblock_id,
                                    'type'=>'button',
                                    'data-toggle'=>'tooltip',
                                    'title'=>'Сдача в аренду',
                                    'width'=>'50px',
                                )).
                                Html::tag('i', '', array('class' => 'fa fa-cart-plus')).
                                Html::endTag('button');
                            }

                        }

                    ],
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute'=>'priceSale',
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
                                'data-id'=>$model->id,
                                'data-pricesale'=>$model->priceSale,
                                'data-orderblock_id'=>$orderblock_id,
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
                'attribute'=>'categoriesArray',
                'format'=>'raw',
                'value' => implode(', ', ArrayHelper::map($model->categories, 'id', 'name')),
                'type'=>DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => Category::find()->select(['name','id'])->indexBy('id')->column(),
                    'options' => ['placeholder' => 'Выберите категорию ...','multiple' => true],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ],
            ],
            [
                'attribute'=>'tagsArray',
                'format'=>'raw',
                'value' => $model->tag,
                'type'=>DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data'=> \common\models\Tag::getAllTags(),
                    'options' => [
                        'placeholder' => 'Установите теги ...',
                        'multiple' => true
                    ],
                    'pluginOptions' => [
                        'tags' => true,
                        'tokenSeparators' => [','],
                        'maximumInputLength' => 20,
                        'allowClear' => true,
                    ],
                ],
            ],
        ];
        foreach ($productAttributes as $productAttribute) {
            $detailViewAttributes[]=[
                'attribute'=>$productAttribute->prodAttribute->attr_name,
                'label'=>$productAttribute->prodAttribute->name,
            ];
        }
        $detailViewAttributes[]=[
            'columns' =>[
                [
                    'group' => true,
                    'label' => 'Доступно для заказа: '.$model->getBalance().'<br>Всего в наличии на складе: '.$model->getBalanceStock(). " шт. ",
                    'rowOptions' => ['class' => 'info'],
                ],
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
                            'data-id'=>$model->id,
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
        'mode'=>$edit?DetailView::MODE_EDIT:DetailView::MODE_VIEW,
        'id'=> 'detail-view',
        'formOptions' => [
            'id' => 'form-detail-view',
        ],
        'panelCssPrefix'=>'box box-',
        'panel'=>[
            'heading'=>$model->name,
            'type'=>DetailView::TYPE_PRIMARY,

        ],
        'buttons1'=>$btnMotion. ' {update} {delete} {reset} '.$btnClose,
        'buttons2'=>$btnMotion. ' {view}  {save} {delete} {reset} '.$btnClose,
        'deleteOptions'=>[
            'url'=>['delete', 'id' => $model->id],
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

<?=
$this->render('modalUploadFile', [
]);
?>
<?php
$url=Url::toRoute("product/update-ajax").'?id='.$model->id;
$urlModalPjax=Url::toRoute("file/index").'?hash=';
$urlOrder_index_ajax=Url::toRoute("movement/index-ajax");

$urlProduct=$model->getUrl($category->alias);
$urlUpdCatalog=Url::toRoute(['category/']).$category->alias;
$urlUpdProduct=Url::toRoute(['product/update-ajax','id'=>$model->id,'category'=>$category->id]);
$js = <<<JS
    $(document).ready ( function(){
        //меняем url
        //  window.history.pushState(null,"$model->name","$urlProduct");
        //активирум раздел в дереве
        if ($("#fancyree_w1").length) {
            var fancyree=$("#fancyree_w1");
            if (!(fancyree.fancytree("getActiveNode"))) {
                var tree= fancyree.fancytree("getTree")
                var key = treeFindKeyById(tree.toDict(true),"$category->id");
                //передаем параметр, что бы не перезагрузать правую часть
                // console.log(key);
                var node=fancyree.fancytree("getTree").getNodeByKey(key);
                // console.log(node);
                node.notPjax=1;
                fancyree.fancytree("getTree").activateKey(key);    
            }
        }
    });

    //функция поиска node по id
    function treeFindKeyById(tree,id) {
        var key;
        // console.log(id);
        if (tree.data) {
            if (tree.data.id==id) {
                return tree.key;
            }    
        }
        if (tree.children) {
            var index, len;
            for (index = 0, len = tree.children.length; index < len; ++index) {
                // console.log(tree.children[index]);
                if (key=treeFindKeyById(tree.children[index], id)) {
                    return key;
                }
            }
        }
        return false;
    };

        // $('form').on('beforeSubmit', function(){
        //     console.log('событие');
        $('#form-detail-view').on('beforeSubmit', function(){
        // $('#form-detail-view').submit(function(){
        // $("body").on("beforeSubmit", '#form-detail-view', function() {
            var data = $(this).serialize();
            $.ajax({
                url: "$urlUpdProduct",
                type: 'POST',
                data: data,
                success: function(response){
                    $("#pjax_right-detail").html(response);
                    // $.pjax.reload({container: "#pjax_alerts", async: false});
                },
                error: function(){
                    alert('Error!');
                }
            });
            return false;
        });
    $(".uplImgProduct").click(function () {
//        $("#modalUploadFileContent").data("hash",this.dataset.hash);
       $("#modalUploadFile").modal("show");
       $("#modalUploadFileContent").data("hash",this.dataset.hash);
       $("#modalUploadFileContent").data("product_id","$model->id");
       $("#modalUploadFileContent").data("alias","$category->alias");
//        $("#modalUploadFileContent").data("contract_id",this.dataset.contract_id);
//        $.pjax.reload({
//            url        : "$urlModalPjax"+$("#modalUploadFileContent").data("hash"),
//            replace: false,
//            container:"#grid-files"
//        }); 
    });
    $(".kv-btn-close").click(function () {
        $(".tooltip").remove();
        $.pjax.reload({
            url:"$urlUpdCatalog",
            replace: false,
            push: true,
            type: "POST",
            container:"#pjax_right-detail"
        });
    });
    
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
    //открываем окно с добавлением движения
    $("#btnMotion").click(function () {
        // alert("Открываем окно для редактирования движений");
        // console.log(this.dataset.url);
        $.ajax({
                url: this.dataset.url,
                type: 'POST',
                data: {'id' : this.dataset.id},
                success: function(response){
                    $("#modalBlock").html(response.data)
                    $('#modal').removeClass('fade');
                    $('#modal').modal('show');
                    //TODO: приходится перезагрзуать pjax grid. Т.к. не работают Editable
                    // $.pjax.reload({
                    //     container: "#pjax_movement_grid", 
                    //     async: false
                    // });
                },
                error: function(){
                    alert('Error!');
                }
        });
        return false;
//
    });
    
    $("#opencalendar").click(function () {
        $.ajax({
                url: this.dataset.url,
                type: 'POST',
                data: {'id' : this.dataset.id},
                success: function(response){
                    $("#modalBlock").html(response.data)
                    $('#modal').removeClass('fade');
                    $('#modal').modal('show');
                },
                error: function(){
                    alert('Error!');
                }
        });
        return false;
//
    });
JS;
$this->registerJs($js);
?>
<?php Pjax::end(); ?>
