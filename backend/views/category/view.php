<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\editable\Editable;
use yii\widgets\ListView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $productsDataProvider \yii\data\ActiveDataProvider */

?>
<div class="box box-primary" id="cat-info">
    <div class="box-header with-border">
        <h3 class="box-title"><?= Editable::widget([
            'model'=>$model,
            'attribute' => 'name',
            'asPopover' => false,
            'header' => 'Категория',
            'format' => Editable::FORMAT_BUTTON,
            'options' => [
                    'class'=>'form-control',
                    'prompt'=>'Введите название категории',
                    'id'=> 'category-'.$model->id,
            ],
            'pluginEvents' => [
                "editableSuccess"=>"function(event, val, form, data) {
                    var node = $('#fancyree_w0').fancytree('getActiveNode');
                    if( !node ) return;
                    node.setTitle(val);
     
                    $.pjax.reload({container: '#pjax_alerts', async: false}); 
                }",
                "editableError"=>"function(event, val, form, data) { $.pjax.reload({container: '#pjax_alerts', async: false});; }",
            ],
        ]);?>
        </h3>
        <div class="box-tools pull-right">
            <button id="delCatalog" type="button" class="btn btn-box-tool">Удалить раздел</i></button>
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body">

    </div>
</div>

    <div class="product-filter clearfix">
        <div class="row">
            <div class="col-md-2">
            </div>
            <div class="col-md-4">
            </div>
            <div class="col-md-2">
            </div>
            <div class="col-md-2">
            </div>
            <div class="col-md-2 text-right">
                <div class="button-view">
                    <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="Списком"><i class="fa fa-th-list"></i></button>
                    <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="Таблицей"><i class="fa fa-th"></i></button>
                </div>
            </div>
        </div>
    </div>
<?php
    $layout="<div class='nav-cat clearfix'>
                            <div class='pull-left'>
                                {pager}
                            </div>
                            {summary}
                         </div>
                         <div class='lst row'>
                            {items}
                         </div>
                         <div class='nav-cat clearfix'>
                            <div class='pull-left'>
                                {pager}
                            </div>
                            {summary}
                         </div>
                        ";
?>
<?= ListView::widget([
    'dataProvider' => $productsDataProvider,
    'itemView' => '_product',
    'layout' => $layout,
    'summary' => "<div class='pull-left nam-page'>Показано c {begin} по {end} из {totalCount} (всего {pageCount} страниц)</div>",
    'summaryOptions' => [
        'tag' => 'div',
        'class' => 'pull-left nam-page',
    ],
    'options' => [
        'tag' => 'div',
        'id' => 'productList',
    ],
    'itemOptions' => [
        'tag' => 'div',
//                    'class' => 'product-layout product-block col-xs-12',
        'class' => 'product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12',

    ],
]) ?>


<?php
$urlProduct_update_ajax=Url::toRoute("product/update-ajax");
$js = <<<JS
     $("#delCatalog").click(function () {
            var node = $("#fancyree_w0").fancytree("getActiveNode");
                // console.log(node);
            if (node.hasChildren()) {
                alert("Раздел не пустой. Удалении не возможно");
                return false;
            }
    
            $.get("del-ajax",{id: node.data.id }, function(response){
               // console.log(response);
                if (response.status=="success") {
                    node.remove()
                }
                $.pjax.reload({container: "#pjax_alerts", async: false});
            });
     });
    $(".viewProduct").click(function() {
        var id=this.closest('.product-layout').dataset.key;
        var node = $("#fancyree_w0").fancytree("getActiveNode");
        $.get("$urlProduct_update_ajax",{id: id,category:node.data.id }, function(response){
            // console.log(response);
            $("#right-detail").html(response)
        });
    });
    
    // Метод elem.closest(css) для поиска ближайшего родителя, удовлетворяющего селектору css, не поддерживается некоторыми браузерами, например IE11-.
    (function() {
        // проверяем поддержку
        if (!Element.prototype.closest) {
            // реализуем
            Element.prototype.closest = function(css) {
              var node = this;
              while (node) {
                if (node.matches(css)) return node;
                else node = node.parentElement;
              }
              return null;
            };
        }
    })();
    
JS;
$this->registerJs($js);
?>