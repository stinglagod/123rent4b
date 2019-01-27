<?php

use yii\web\JsExpression;
use kartik\editable\Editable;
use yii\helpers\Url;
use yii\widgets\Pjax;
$this->title="Каталог";
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary сollapsed-box">
                    <div class="box-header with-border">
                        <div class="input-group input-group-sm" style="width: 95%">
                            <input type="text" name="table_search" class="form-control pull-left" placeholder="Поиск">
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" title="Раскрыть фильтр" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        поля поиска
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <br>
                        <div class="box-tools pull-right">
                            <button id="addCatalog" type="button" class="btn btn-box-tool" title="Добавить раздел"><i class="fa fa-folder-o"></i></button>
                            <button id="addProduct" type="button" class="btn btn-box-tool" title="Добавить товар"><i class="fa fa-file-o"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <?=
                        \wbraganca\fancytree\FancytreeWidget::widget([
                            'options' =>[
                                'source' => $tree,
                                'extensions' => ['dnd'],
                                'dnd' => [
                                    'preventVoidMoves' => true,
                                    'preventRecursiveMoves' => true,
                                    'autoExpandMS' => 400,
                                    'dragStart' => new JsExpression('function(node, data) {
                                    return true;
                                }'),
                                    'dragEnter' => new JsExpression('function(node, data) {
                                    return true;
                                }'),
                                    'dragDrop' => new JsExpression('function(node, data) {
//                                    console.log(data.otherNode.data.id);
//                                    console.log(node.data.id);
                                    $.get("'.Url::toRoute(['category/move']).'",{item: data.otherNode.data.id, action: data.hitMode, second: node.data.id}, function(response){
//                                        console.log(response);
                                        if (response.status=="success") {
                                            data.otherNode.moveTo(node, data.hitMode);
                                            window.history.pushState(null,null,response.data);
                                        }
                                         $.pjax.reload({container: \'#pjax_alerts\', async: false});
                                    });
                                }'),
                                ],
                                'activate' => new JsExpression('function(event,data) {
//                                  Что бы дважды не перезагружать. В случае если страница открыта по ссылке
                                    if (data.node.notPjax === undefined) {
                                        var id = data.node.data.id;
                                        var alias = data.node.data.alias;
                                        $.pjax.reload({
//                                            url:"'.Url::toRoute(['category/view-ajax']).'?id="+id,
                                            url:"'.Url::toRoute(['category/']).'"+alias,
                                            replace: false,
                                            push: true,
                                            type: "POST",
                                            container:"#pjax_right-detail"
                                        });
                                    }
                            }')
                            ]
                        ]);
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-9" id="right-detail">
                <?php Pjax::begin(['enablePushState' => false,'id' => 'pjax_right-detail']); ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
<?php
$urlAddCatalog=Url::toRoute(['category/add-ajax']);
$urlDelCatalog=Url::toRoute(['category/del-ajax']);
$urlUpdProduct=Url::toRoute(['product/update-ajax']);
$js = <<<JS
    $(document).ready ( function(){
        //Если есть чего загружать, то загружаем в правую часть
        if ("$urlRightDetail") {
            $.pjax.reload({
                url:"$urlRightDetail",
                replace: false,
                container:"#pjax_right-detail",
                timeout: false,
            });    
        };
    });
    //Добавление нового каталога
    $("#addCatalog").click(function () {
        var fancyree=$("#fancyree_w0");
        if (fancyree.length) {
            var parent = $("#fancyree_w0").fancytree("getActiveNode");
            if ((parent)) {
                $.get("add-ajax",{parent: parent.data.id }, function(response){
    //            console.log(response);
                    if (response.status=="success") {
                        var child=parent.addChildren({
                            title: response.out.name,
                            folder: true,
                            id: response.out.id
                        });
                        child.setActive();
                    }
                });
//                $.pjax.reload({
//                    url:"$urlAddCatalog"+"?parent="+parent.data.id,
//                    replace: false,
//                    container:"#pjax_right-detail",
//                    timeout: false,
//                });
                $.pjax.reload({container: "#pjax_alerts", async: false});
            }else {
                alert("Выберите раздел");
                return false;
            }    
        }
    });
    //  Добавление нового товара
    $("#addProduct").click(function () {
        var fancyree=$("#fancyree_w0");
        if (fancyree.length) {
            var parent = $("#fancyree_w0").fancytree("getActiveNode");
            if (parent) {
                $.pjax.reload({
                    url:"$urlUpdProduct"+"?category="+parent.data.id,
                    replace: false,
                    container:"#pjax_right-detail",
                    timeout: false,                    
                });
            } else {
                alert("Выберите раздел");
                return false;
            }
        }
    });

JS;

$this->registerJs($js);
?>