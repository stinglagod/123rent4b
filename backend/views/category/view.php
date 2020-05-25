<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\editable\Editable;
use yii\widgets\ListView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Category Если для поиска тогда 1, иначе текущая категория */
/* @var $productsDataProvider \yii\data\ActiveDataProvider */
/* @var $orderblock_id integer */
/* @var $parent_id integer */

?>
<div class="box box-primary" id="cat-info">
    <div class="box-header сollapsed-box with-border">
        <?php if (is_object($model)) { ?>
        <h3 class="box-title"><?= Editable::widget([
            'model'=>$model,
            'attribute' => 'name',
            'asPopover' => false,
            'header' => 'Категория',
            'format' => Editable::FORMAT_BUTTON,
            'formOptions' => [
                'method' => 'post',
                'action' => Url::to(['/category/view-ajax','category_id'=>$model->id])
            ],
            'options' => [
                    'class'=>'form-control',
                    'prompt'=>'Введите название категории',
                    'id'=> 'category-'.$model->id,
                'pluginOptions'=>[
                    'url' => Url::to(['/category/view-ajax'])
                ]
            ],
            'pluginEvents' => [
                "editableSuccess"=>"function(event, val, form, data) {
                    window.history.pushState(null,val,data.data.url);
                    $.pjax.reload({
                        container: '#pjax_left-tree', 
                        async: true,
                        data: {active_id:\"$model->id\"},
                        type       : 'POST',
                        push: false
                    });
                    treeActivateId(\"$model->id\");
                    window.history.pushState(null,val,data.data.url);
                }",
                "editableError"=>"function(event, val, form, data) { 
                    $.pjax.reload({
                        container: '#pjax_alerts', 
                        async: false,
                        url: '/admin/category/tree',
                        push: false
                      }); 
                }",
            ],
        ]);?>
        </h3>
        <div class="box-tools pull-right">
            <button id="delCatalog" type="button" class="btn btn-box-tool" title="Удалить раздел"><i class="fa fa-trash-o"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>

        <?php } else if ($model==1){ ?>
            <h3 class="box-title">Результаты поиска</h3>
        <?php } ?>
    </div>
    <div class="box-body">
        <?php $form = ActiveForm::begin([
            'action'=> Url::to(['/category/update-ajax','category_id'=>$model->id]),
            'id' =>'upd_category'
        ])?>
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'on_site')->checkbox(['class' => 'upd_category',]); ?>
            </div>
<!--            <div class="col-md-4">-->
<!--                --><?//= $form->field($model, 'on_site')->textInput(['class' => 'upd_category',]); ?>
<!--            </div>-->
<!--            <div class="col-md-4">-->
<!--                <button class="btn btn-default uplImgCatagory center-block" data-hash="--><?//=$model->hash?><!--"  type="button"><i class="glyphicon glyphicon-download-alt" aria-hidden="true"></i>Загрузить изображения</button></div>-->
<!--                --><?//= $form->field($model, 'thumbnail_id')->textInput(['class' => 'upd_category',]); ?>
<!--            </div>-->

        </div>
        <?php ActiveForm::end(); ?>
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
    'viewParams' => [
        'category'=>(is_object($model))?$model:null,
        'orderblock_id'=>$orderblock_id,
        'parent_id'=>$parent_id,
    ],
]) ?>

<?=
$this->render('_modalUploadFile', [
    'model' => $model
]);
?>

<?php
//$urlUpdProduct=Url::toRoute("product/update-ajax");
$urlUpdProduct=Url::toRoute("category/").((is_object($model))?$model->alias:'');
//$urlCategory=$model->getUrl();
$urlDelCatalog=Url::toRoute(['category/del-ajax']);
$category_id=(is_object($model))?$model->id:null;
$js = <<<JS
    $(document).ready ( function(){
        //активирум раздел в дереве
        if ("$category_id") {
            treeActivateId("$category_id")    
        }
        
    });
    function treeActivateId(id)
    {
        if ($("#fancyree_w1").length) {
            var fancyree=$("#fancyree_w1");
            if (!(fancyree.fancytree("getActiveNode"))) {
                var tree= fancyree.fancytree("getTree")
                var key = treeFindKeyById(tree.toDict(true),id);
                //передаем параметр, что бы не перезагрузать правую часть
                // console.log(key);
                var node=fancyree.fancytree("getTree").getNodeByKey(key);
                // console.log(node);
                node.notPjax=1;
                fancyree.fancytree("getTree").activateKey(key);    
            }
        }
    }
    //функция поиска node по id
    function treeFindKeyById(tree,id) {
        var key;
        // console.log(tree);
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
    
     $("#delCatalog").click(function () {
        if ($("#fancyree_w1").length) {
            var fancyree=$("#fancyree_w1");
            var node = fancyree.fancytree("getActiveNode");
            if (node.hasChildren()) {
                alert("Раздел не пустой. Удалении не возможно");
                return false;
            }
            
            $.get("$urlDelCatalog",{id: node.data.id }, function(response){
               // console.log(response);
                if (response.status=="success") {
                    var key=node.parent.key;
                    node.remove()
                //  активируем родителя
                    fancyree.fancytree("getTree").activateKey(key);    
                }
                $.pjax.reload({container: "#pjax_alerts", async: false});
            });
        }
     });
    //Открываем продукт
    $(".viewProduct").click(function() {
        var id=this.closest('.product-layout').dataset.key;
        var param=$(location).attr("search");
        var url=this.dataset.url;
        $.pjax.reload({
            url: url + param,
            replace: false,
            push: true,
            type: "POST",
            container:"#pjax_right-detail",
            timeout: false,                    
        });
        return false;
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
    $("body").on("submit", "#upd_category", function(e) {
        var form=$(this).closest('form');
        e.preventDefault();
        var formData = form.serialize();
        // console.log(form);
        
        $.post({
               url: form[0].action,
               dataType: 'json',
               data: formData,
               success: function(response) {
               },
        });
        
    })
    $("body").on("change", ".upd_category", function(e) {
        $(this).closest('form').submit();
        // console.log($(this).closest('div'));
        $(this).closest('div').removeClass("has-success");
        
    });
    
    $(".uplImgCatagory").click(function () {
        alert('tut');
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

JS;
$this->registerJs($js);
?>