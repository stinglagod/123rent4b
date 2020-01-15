<?php

use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\Url;
use  common\models\Action;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use \common\models\Status;
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 24.12.2018
 * Time: 23:32
 */
/* @var $model common\models\Order */
/* @var $blocks \common\models\Block[] */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-3">
            <?=
            $form->field($model, 'dateBegin')->widget(DateControl::class, [
                'type'=>DateControl::FORMAT_DATE,
                'ajaxConversion'=>false,
                'widgetOptions' => [
                    'pluginOptions' => [
                        'autoclose' => true
                    ]
                ]
            ])
            ?>
        </div>
        <div class="col-md-3">
            <?=
            $form->field($model, 'dateEnd')->widget(DateControl::class, [
                'type'=>DateControl::FORMAT_DATE,
                'ajaxConversion'=>false,
                'widgetOptions' => [
                    'pluginOptions' => [
                        'autoclose' => true
                    ]
                ]
            ])
            ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'customer')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-8">
            <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'status_id')->dropDownList(ArrayHelper::map($statuses, 'id', 'name'), ['prompt' => Yii::t('app', 'Выберите')]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'responsible_id')->dropDownList(ArrayHelper::map($users, 'id', 'shortName'), ['prompt' => Yii::t('app', 'Выберите')]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?=$model->status->name?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php Pjax::begin(['id'=>'sum-order-pjax']); ?>
            Сумма заказа: <?=$model->summ?>
            <br>
            Оплачено: <?=$model->paid?>
            <br>
            Остаток: <?=($model->summ - $model->paid)?>
            <br>
            <?php Pjax::end(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="btn-group pull-left" role="group" aria-label="toolbar">
                <button type="button" class="btn btn-success order-change-status <?=$model->canChangeStatus(Status::CLOSE)?'':'disabled'?>" data-order_id="<?=$model->id?>" data-status_id="<?=Status::CLOSE?>" title="Закрыть заказ">Закрыть заказ</button>
                <button type="button" class="btn btn-danger order-change-status <?=$model->canChangeStatus(Status::CANCELORDER)?'':'disabled'?>" data-order_id="<?=$model->id?>" data-status_id="<?=Status::CANCELORDER?>" title="Отменить заказ">Отменить заказ</button>
            </div>
        </div>
        <div class="col-md-6">
            <div class="btn-group pull-right" role="group" aria-label="toolbar">
                <button type="button" class="btn btn-warning" id="order-export-to-excel" title="Выгрузить в Excel"><span class="fa fa-file-excel-o" aria-hidden="true"></button>
                <div class="btn-group">
                    <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Добавить блок<span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a href="#" class="lst_addblock" data-block_name="<Новый блок>" data-block_id="0"><?=\common\models\Block::getDefaultName()?></a></li>
                        <li class="divider"></li>
                        <?php
                            foreach ($blocks as $block){
                                echo '<li><a href="#" class="lst_addblock" data-block_name="'.$block['name'].'" data-block_id="'.$block['id'].'">'.$block['name'].'</a></li>';
                            }
                        ?>
                    </ul>
                </div>
                <div class="btn-group">
                    <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Добавить услуги<span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <?php

                            foreach (\common\models\Service::getAll() as $service){
                                /* @var $service common\models\Service */
                                ?>
                                <li><a href="#" class="lst_addservice" data-service_id="<?=$service->id?>" <?=$service->id?>" "><?=$service->name?></a></li>
                        <?php
                            }
                        ?>
                    </ul>
                </div>
                <div class="btn-group">
                    <button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle">Операция <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a href="#" class='lst_operation' data-operation_id="<?=Action::ISSUE?>" data-all="1">Выдать ВСЁ</a></li>
                        <li><a href="#" class='lst_operation' data-operation_id="<?=Action::ISSUE?>" data-all="0">Выдать отмеченные</a></li>
                        <li><a href="#" class='lst_operation' data-operation_id="<?=Action::RETURN?>"data-all="1">Получить ВСЁ</a></li>
                        <li><a href="#" class='lst_operation' data-operation_id="<?=Action::RETURN?>"data-all="0">Получить отмеченные</a></li>
<!--                        <li><a href="#" class='lst_operation' data-operation_id="--><?//=Action::TOREPAIR?><!--">Отправить в ремонт</a></li>-->
<!--                        <li><a href="#" class='lst_operation' data-operation_id="--><?//=Action::TOREPAIR?><!--">Получить из ремонта</a></li>-->
<!--                        <li><a href="#" class='lst_operation' data-operation_id="0">Удалить отмеченные</a></li>-->
                    </ul>
                </div>

                <button type="submit" class="btn btn-success">Сохранить</button>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <br>
    <div class="row">
        <div class="col-md-12" id="orderBlank">
            <?php
            foreach ($model->getOrderProductsByBlock() as $block) {
                echo $this->render('_orderBlock', [
                    'block'=>$block,
                ]);
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" id="service">
            <?php
                $dataProviderService=new ActiveDataProvider([
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                    'query' => $model->getServicesQuery(),
                ]);
                echo $this->render('_services',[
                    'services'=>$model->getServices(),
                    'dataProviderService'=>$dataProviderService,
                ]);
            ?>
        </div>
    </div>

</div>
<script>
    function reloadOrderBlock(orderBlock_id) {
        var pjaxContainers = ['#pjax_alerts', '#pjax_order-product_grid_'+orderBlock_id+'-pjax', '#order-movement-grid-pjax'];

        $.each(pjaxContainers , function(index, container) {
            if (index+1 < pjaxContainers.length) {
                $(container).one('pjax:end', function (xhr, options) {
                    $.pjax.reload({container: pjaxContainers[index+1]}) ;
                });
            }
        });

        $.pjax.reload({container: pjaxContainers[0]}) ;
    };


</script>
<?php
$urlContentConfirmModal=Url::toRoute(["order/content-confirm-modal-ajax",'order_id'=>$model->id]);
$urlAddOrderBlock=Url::toRoute(["order/add-block-ajax",'order_id'=>$model->id]);
$urlDelOrderBlock=Url::toRoute(["order/delete-block-ajax"]);
$urlAddProductAjax=Url::toRoute(["order/add-product-ajax"]);
$urlAddCashModal=Url::toRoute("order/add-cash-modal-ajax");
$urlExportOrder=Url::toRoute(["order/export",'order_id'=>$model->id]);
$urlAddService=Url::toRoute(["order/add-service-ajax",'order_id'=>$model->id]);
$urlUpdateOrderProductAjax=Url::toRoute(["order-product/update-ajax"]);
$dateBegin=$model->dateBegin;
$dateEnd=$model->dateEnd;
$_csrf=Yii::$app->request->getCsrfToken();
$js = <<<JS
    $("body").on("click", '.lst_operation', function(e) {
        // alert('Выполняем операцию');
        var length=0;
        var allKeys=[];
        
        if (this.dataset.all==0) {
            $('.grid-orderproduct').each(function(i,elem) {
                var keys=$(this).yiiGridView('getSelectedRows');
                // console.log(keys);
                if (keys.length) {
                    length+=keys.length
                    allKeys=allKeys.concat(keys)    
                }
                
            });
    
           if (length==0) {
               alert('Не выделено ни одного элемента');
               return false;
           }    
        } else {
            allKeys=null;
        }
        
       // console.log(e.params.args.data.id);
       // console.log(this.dataset.operation_id);
        $.post({
           url: "$urlContentConfirmModal", // your controller action
           dataType: 'json',
           data: {
                keylist: allKeys,
                operation: this.dataset.operation_id
           },
           success: function(response) {
               // console.log(response);
               if (response.status === 'success') {
                    $("#modalBlock").html(response.data)
                    $('#modal').removeClass('fade');
                    $('#modal').modal('show'); 
               }
           },
        });
        return false;
    })
    //Добавление нового блока
    $("body").on("click", '.lst_addblock', function() {
        // alert('Добавляем нвоый блок')
        var url="$urlAddOrderBlock"+'&block_name='+this.dataset.block_name
        $.ajax({
            url: url,
            type: "POST",
            data: {
                 _csrf : "$_csrf"
             },
            success: function (data) {
               $("#orderBlank").append(data.html);
               $.pjax.reload({container: "#pjax_alerts", async: false});
            }
        });
        return false;
    });
    $("body").on("click", '.lst_deleteblock', function() {
        // alert('Удаляем блок')
        block_id=this.dataset.block_id;
        url="$urlDelOrderBlock"+'?orderblock_id='+block_id
        $.ajax({
            url: url,
            type: "POST",
            data: {
                 _csrf : "$_csrf"
             },
            success: function (data) {
                if (data.status=='success') {
                    $("#block_"+block_id).remove();     
                } 
               $.pjax.reload({container: "#pjax_alerts", async: false});
            }
        });
        return false;
    })
    
    //вызов добавление товара из заказа
    $("body").on("click", '.lst_addproduct', function() {
        var orderblock_id=this.dataset.block_id;
        var param='?orderblock_id='+orderblock_id+'&dateBegin='+"$dateBegin"+'&dateEnd='+"$dateEnd";
        var parent_id=this.dataset.parent_id;
        
        if (parent_id) {
            if (parent_id=='new') {
                $.post({
                    url: "$urlAddProductAjax", // your controller action
                    dataType: 'json',
                    data: {
                        orderblock_id: orderblock_id,
                        parent_id: 'new'
                    },  
                    success: function(response) {
                        if (response.status === 'success') {
                            reloadPjaxs('#pjax_alerts', '#pjax_order-product_grid_'+orderblock_id+'-pjax', '#sum-order-pjax','#order-movement-grid-pjax')
                        }
                    },
                });
                return false;
            }
            param+='&parent_id='+parent_id;
        }
        var catalog = window.open("/admin/category/tree"+param, "hello", "width=1024,height=600");
        return false;
    });
    
//    Добавление платежа
//вызов добавление товара из заказа
    $("body").on("click", '.lst_addCash', function() {
        var url="$urlAddCashModal"+'?order_id='+this.dataset.order_id;
        $.post({
           url: url,
           type: "POST",
           data: {
                 _csrf : "$_csrf"
           },
           success: function(response) {
               if (response.status === 'success') {
                    $("#modalBlock").html(response.data)
                    $('#modal').removeClass('fade');
                    $('#modal').modal('show'); 
               }
           },
        });
        return false;
    });
    $("body").on("click", '#order-export-to-excel', function() {
        // alert('Выгружаем заказ');
        var url="$urlExportOrder";
        $.post({
           url: url,
           type: "POST",
           data: {
                 _csrf : "$_csrf"
           },
           success: function(response) {
               if (response.status === 'success') {
                   document.location.href=response.data;
               }
           },
        });
    })
    //Добавление новой услуги
    $("body").on("click", '.lst_addservice', function() {
//        alert('Добавляем услугу')
        var url="$urlAddService"+'&service_id='+this.dataset.service_id
        $.ajax({
            url: url,
            type: "POST",
            data: {
                 _csrf : "$_csrf"
             },
            success: function (data) {
                // console.log(data);
                // reloadPjaxs('#pjax_alerts', '#grid-orderservice')
               // $("#service").html(data.html);
               // $.pjax.reload({container: "#pjax_alerts", async: false});
               reloadPjaxs('#pjax_alerts', '#pjax_orderservice_grid-pjax')
            }
        });
        return false;
    });
    //при изменения checkbox is_montage. не нашел как реализовать через картик. Поэтому изобретаю велосипед
    $("body").on("change", '.chk_is_montage', function(e) {
        var checkbox=0;
        var oldcheckbox=0;
        if(this.checked) {
            checkbox=1;
            oldcheckbox=0;
            // alert('истина');
        } else {
            checkbox=0;
            oldcheckbox=1;
            // alert('ложль');
        }
        var elcheckbox=this;
        var url="$urlUpdateOrderProductAjax"+'?id='+this.dataset.orderproduct_id;
        $.ajax({
            url: url,
            type: "POST",
            async: true,
            data: {
                 _csrf : "$_csrf",
                 'hasEditable' : 1,
                 'editableAttribute' : 'is_montage',
                 'is_montage' : checkbox
             },
            success: function (data) {
                var data = JSON.parse(data);
                if (data.output) {
                    elcheckbox.checked = !elcheckbox.checked;
                    reloadPjaxs('#pjax_alerts', '#pjax_orderservice_grid-pjax')
                    // $.pjax.reload({container: "#pjax_alerts", async: false});
                } else {
                    reloadPjaxs('#pjax_orderservice_grid-pjax','#sum-order-pjax');
                }
            },
            error: function(data) {
                elcheckbox.checked = !elcheckbox.checked;
                $.pjax.reload({container: "#pjax_alerts", async: false});
            }
        });
        // console.log(this.checked);
    });
    
    // Изменение статуса
    $("body").on("click", '.order-change-status', function(e) {
         $.get({
            url: '/admin/order/update-status-ajax',
            data: {
                order_id: this.dataset.order_id,
                status_id: this.dataset.status_id
            },
            success: function() {
                reloadPjaxs('#sum-order-pjax','#pjax_alerts');
            }
         }).fail(function() { alert("error"); });
    })
     
JS;
$this->registerJs($js);

?>