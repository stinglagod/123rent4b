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
                                'source' => $data,
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
                                    $.get("move",{item: data.otherNode.data.id, action: data.hitMode, second: node.data.id}, function(response){
//                                        console.log(response);
                                        if (response.status=="success") {
                                            data.otherNode.moveTo(node, data.hitMode);
                                        }
                                         $.pjax.reload({container: \'#pjax_alerts\', async: false});
                                    });
                                }'),
                                ],
                                'activate' => new JsExpression('function(event,data) {
                                    var id = data.node.data.id;
                                    $.get("view-ajax", {id:id},function(data){
                                        $("#right-detail").html(data)
                                    });
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


$this->registerJs('
    function reloadRightDetail(category) {
        $.get("view-ajax", {id:category},function(data){
            $("#right-detail").html(data)
        });
    }

    $("#addCatalog").click(function () {
        var parent = $("#fancyree_w0").fancytree("getActiveNode");
        if (!(parent)) {
            alert("Выберите раздел");
        }
//        console.log(parent);
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
            $.pjax.reload({container: "#pjax_alerts", async: false});
        });
    });
    $("#addProduct").click(function () {
        var parent = $("#fancyree_w0").fancytree("getActiveNode");
        id=(parent)?parent.data.id:null;
//        console.log(parent);
        $.get("'.Url::toRoute("product/update-ajax").'",{category: id }, function(response){
//            console.log(response);
            $("#right-detail").html(response)
        });
    });
');
?>