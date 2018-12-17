<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\models\Category;
use yii\helpers\ArrayHelper;
use kartik\detail\DetailView;
use common\models\File;

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

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
    $sliderBlock.='<button class="btn btn-default uplImgPoint center-block" data-hash="'.$model->hash.'"  type="button"><i class="glyphicon glyphicon-download-alt" aria-hidden="true"></i>Загрузить изображения</button></div>';

    $btnClose='<button type="reset" class="kv-action-btn kv-btn-close" title="" data-toggle="tooltip" data-container="body" data-original-title="Закрыть"><span class="fa fa-close"></span></button>';
    $btnMotion='<button type="reset" id="btnMotion" class="kv-action-btn" title="" data-toggle="tooltip" data-container="body" data-original-title="Приход/Уход">Приход/Уход</button>';
    ?>


    <?php $calendar = \yii2fullcalendar\yii2fullcalendar::widget(array(
//        'events'=> $events,
        'events' => Url::to(['product/calendar-ajax','product_id'=>$model->id])
    )) ?>

    <?=
    DetailView::widget([
        'model'=>$model,
        'condensed'=>true,
        'hover'=>true,
        'mode'=>DetailView::MODE_VIEW,
        'panel'=>[
            'heading'=>$model->name,
            'type'=>DetailView::TYPE_INFO,
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
        'attributes'=>[
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
                'columns' =>[
                    [
                        'attribute'=>'cost',
//                        'type'=>DetailView::INPUT_MONEY,
                        'valueColOptions'=>['style'=>'width:30%']
                    ],
                    [
                        'attribute'=>'primeCost',
                        'valueColOptions'=>['style'=>'width:30%']
                    ]
                ],
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
                    'options' => ['placeholder' => 'Выберите декларанта ...','multiple' => true],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ],
            ],
            [
                'columns' =>[
                    [
                        'group' => true,
                        'label' => 'В наличии на даты: ',
                        'rowOptions' => ['class' => 'info'],
                    ],
                    [
                        'group' => true,
                        'label' => '<a id="opencalendar" href="#" data-id="'.$model->id.'">Открыть календарь</a>',
                        'valueColOptions'=>['style'=>'width:30%']
                    ]
                ],
            ],
//            [
//                'group' => true,
//                'label' => 'В наличии на даты: ',
//                'rowOptions' => ['class' => 'info'],
//            ],
//            [
//                'group' => true,
//                'label' => $calendar,
////                'rowOptions' => ['class' => 'info'],
//            ],
        ],
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
$js = <<<JS
        function reloadRightDetail(category) {
            var node = $("#fancyree_w0").fancytree("getActiveNode");
            category=category?category:node.data.id;
            console.log(category);
            $.get("view-ajax", {id:category},function(data){
                // console.log(data);
                $("#right-detail").html(data)
                $.pjax.reload({container: "#pjax_alerts", async: false});
            });
        };

        $('form').on('beforeSubmit', function(){
            var data = $(this).serialize();
            // console.log("$category");
            // console.log(data);return false;
            $.ajax({
                url: "$url",
                type: 'POST',
                data: data,
                success: function(response){
                    // console.log(response);
                    reloadRightDetail("$category");
                },
                error: function(){
                    alert('Error!');
                }
            });
            return false;
        });
    $(".uplImgPoint").click(function () {
//        $("#modalUploadFileContent").data("hash",this.dataset.hash);
       $("#modalUploadFile").modal("show");
       $("#modalUploadFileContent").data("hash",this.dataset.hash);
//        $("#modalUploadFileContent").data("contract_id",this.dataset.contract_id);
//        $.pjax.reload({
//            url        : "$urlModalPjax"+$("#modalUploadFileContent").data("hash"),
//            replace: false,
//            container:"#grid-files"
//        }); 
    });
    $(".kv-btn-close").click(function () {
        $(".tooltip").remove();
        reloadRightDetail()
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
    
    $("#btnMotion").click(function () {
        alert("hello");
//
    });
    
    $("#opencalendar").click(function () {
        alert("hello");
        
        return false;
//
    });
JS;
$this->registerJs($js);
?>